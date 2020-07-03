<?php

namespace App\Http\Controllers\User;

use App\Models\Article;
use App\Models\ArticleRate;
use App\Models\Channel;
use App\Models\Content;
use App\Models\ContentRate;
use App\Models\Event;
use App\Models\Follower;
use App\Models\Login;
use App\Models\Notification;
use App\Models\Record;
use App\Models\Requests;
use App\Models\SellRate;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Usermeta;
use App\Models\UserRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use kcfinder\session;
use Laravel\Socialite\Facades\Socialite;
use Psy\Test\Exception\RuntimeExceptionTest;
use App\Models\Sell;

class UserController extends Controller
{
    public function login(Request $request){
        if($request->session()->has('user')){
            $c = unserialize($request->session()->get('user'));
            if($c['vendor'] == 1)
                return redirect('/user/dashboard');
            else
                return redirect('/user/content');
        }
        if(Cookie::has('user')){
            $cUser = decrypt(Cookie::get('user'));
            $admin = User::where('username',$cUser)->first();
            $request->session()->put('user',serialize($admin->toArray()));
            Event::create([
                'user_id'   => $admin->id,
                'type'      => 'Cookie',
                'ip'        => $request->ip()
            ]);
            return redirect('/user/dashboard');
        }
        if(isset($_GET['redirect'])){
            $request->session()->flash('redirect',$_GET['redirect']);
        }
        return view('user.login.login');
    }
    public function dologin(Request $request){
        $username = $request->username;
        $password = $request->password;
        $admin = User::with('usermetas')->where(function ($w) use($username){
            $w->where('username',$username)->orWhere('email',$username);
        })->where('admin','0')->first();


        if($admin && decrypt($admin->password) == $password){

            if($admin->mode != 'active') {
                if (userMeta($admin->id, 'blockDate', time()) < time()) {
                    $admin->update(['mode'=>'active']);
                } else {
                    $jBlockDate = date('d F Y', userMeta($admin->id, 'blockDate', time()));
                    return redirect()->back()->with('msg', trans('main.access_denied') . $jBlockDate );
                }
            }

            if(get_option('duplicate_login',0) == 1) {
                $login = Login::where('user_id', $admin->id)->orderBy('id', 'DESC')->first();
                if (isset($login)) {
                    if ($login->created_at_sh + 300 > time()) {
                        return back()->with('msg', trans('main.access_denied_duplicate_login'));
                    }
                }
            }

            $New = Login::create([
                'user_id'       => $admin->id,
                'created_at_sh' => time(),
                'updated_at_sh' => time()
            ]);
            $request->session()->put('user',serialize($admin->toArray()));
            Event::create([
                'user_id'   => $admin->id,
                'type'      => 'Login Page',
                'ip'        => $request->ip()
            ]);
            # check if user want remember
            if($request->remember == 1)
            {
                Cookie::queue('user',encrypt($admin->username),259200);
            }
            User::where('id',$admin->id)->update(['last_view'=>time()]);
            if($request->session()->has('redirect'))
                return redirect($request->session()->get('redirect'));
            else
                return redirect('/user/dashboard');
        }else{
            return redirect('/user/login')->with('msg',trans('main.incorrect_login'));
        }
    }
    public function logout(Request $request){
        $request->session()->flush();
        Cookie::queue(Cookie::forget('user'));
        return back();
    }
    public function active($id){
        $user = User::where('token',$id)->first();

        if($user){
            $user->update(['mode'=>'active']);
            sendMail(['template'=>get_option('user_register_wellcome_email'),'recipent'=>[$user->email]]);
        }else{
            return abort(404);
        }

        return view('user.login.active');
    }
    public function passwordChange(Request $request){
        if($request->password != $request->repassword){
            notify(trans('main.pass_confirmation_same'),'danger');
            return back();
        }else{
            global $user;
            User::find($user['id'])->update(['password'=>encrypt($request->password)]);
            $request->session()->flash('msg','success');
            return back();
        }
    }
    public function avatarChange(Request $request){
        global $user;
        Usermeta::updateOrNew($user['id'],$request->all());
        return back();
    }
    public function profileImageChange(Request $request){
        global $user;
        Usermeta::updateOrNew($user['id'],$request->all());
        return back();
    }
    public function reset(Request $request){
        $str = str_random();
        $update = User::where('email',$request->email)->update(['token'=>$str]);
        if($update) {
            sendMail(['template'=>get_option('user_register_reset_email'),'recipent'=>[$request->email]]);
            return back()->with('msg', trans('main.pass_change_link'));
        }
        else {
            return back()->with('msg', trans('main.user_not_found'));
        }
        return back();
    }
    public function resetToken($token){
        $user = User::where('token',$token)->first();
        $user->update(['password'=>encrypt(str_random(6))]);
        sendMail(['template'=>get_option('user_register_new_password_email'),'recipent'=>[$user->email]]);
        return redirect('/')->with('msg',trans('main.new_pass_email'));
    }

    public function register()
    {
        return view('user.login.register');
    }
    public function doregister(Request $request)
    {
        $duplicateUser = User::where('username',$request->username)->first();
        $duplicateEmail = User::where('email',$request->email)->first();

        if($duplicateUser)
        {
            $request->session()->flash('Error','duplicate_user');
            return redirect()->back()->with('msg',trans('main.user_exists'));
        }
        if($duplicateEmail)
        {
            $request->session()->flash('Error','duplicate_email');
            return redirect()->back()->with('msg',trans('main.user_exists'));
        }
        if($request->password != $request->repassword)
        {
            $request->session()->flash('Error','password_not_same');
            return redirect()->back()->with('msg',trans('main.pass_confirmation_same'));
        }

        $newUser = [
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>encrypt($request->password),
            'create_at'=>time(),
            'admin'=>0,
            'mode'=>get_option('user_register_mode','active'),
            'category_id'=>get_option('user_default_category',0),
            'token'=>str_random(15)
        ];
        $newUser = User::create($newUser);

        ## Send Suitable Email For New User ##
        if(get_option('user_register_mode') == 'deactive')
            sendMail(['template' => get_option('user_register_active_email'), 'recipent' => [$newUser->email]]);
        else
            sendMail(['template'=>get_option('user_register_wellcome_email'),'recipent'=>[$newUser->email]]);

        if(get_option('user_register_mode') == 'active')
            return redirect()->back()->with('msg',trans('main.thanks_reg'));
        else
            return redirect()->back()->with('msg',trans('main.active_account_alert'));
    }

    ## Register Steps
    public function registerStepOne($phone){
        $checkPhone = User::where('username',$phone)->count();
        if($checkPhone > 0)
            return ['status'=>'error','description'=>'duplicate'];

        $random = random_int(11111,99999);
        $newUser = User::create([
            'username'  =>$phone,
            'code'      =>$random,
            'admin'     =>0,
            'create_at' =>time()
        ]);
        if($newUser){
            sendSms($phone,$random);
            return ['status'=>'success','id'=>$newUser->id];
        }
        return ['status'=>'error','description'=>'create'];
    }
    public function registerStepTwo($phone, $code){
        $checkPhone = User::where('username',$phone)->where('mode',null)->where('password',null)->first();
        if(!$checkPhone || $checkPhone->code == null){
            return ['status'=>'error','error'=>'-1','description'=>'not found'];
        }
        if($checkPhone->code != $code){
            return ['status'=>'error','error'=>'0','description'=>'code not correct'];
        }
        return ['status'=>'success'];
    }
    public function registerStepTwoRepeat($phone){
        $checkPhone = User::where('username',$phone)->where('mode',null)->where('password',null)->first();
        if($checkPhone){
            $random = random_int(11111,99999);
            $checkPhone->update(['code'=>$random]);
            sendSms($phone,$random);
            return ['status'=>'success'];
        }
        return ['status'=>'error','error'=>'-1','description'=>'not found'];
    }
    public function registerStepThree($phone,$code,Request $request){
        $checkPhone = User::where('username',$phone)->where('mode',null)->where('password',null)->first();
        if(!$checkPhone || $checkPhone->code == null){
            return ['status'=>'error','error'=>'-1','description'=>'not found'];
        }
        if($checkPhone->code != $code){
            return ['status'=>'error','error'=>'0','description'=>'code not correct'];
        }

        if($request->password != $request->repassword){
            return ['status'=>'error','error'=>'2','description'=>'password not same'];
        }

        $checkPhone->update([
           'password'   =>encrypt($request->password),
           'name'       =>$request->name,
           'email'      =>$request->email,
           'mode'       =>'active',
           'category_id'=>get_option('user_default_category',0),
           'token'      =>str_random(15)
        ]);

        ## Send Suitable Email For New User ##
        /*
        if(get_option('user_register_mode') == 'deactive')
            sendMail(['template' => get_option('user_register_active_email'), 'recipent' => [$checkPhone->email]]);
        else
            sendMail(['template'=>get_option('user_register_wellcome_email'),'recipent'=>[$checkPhone->email]]);
        */

        $request->session()->put('user',serialize($checkPhone->toArray()));
        return ['status'=>'success'];
    }

    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }
    public function googledoLogin(Request $request){
        session()->put('state', $request->input('state'));
        $user = Socialite::driver('google')->user();
        $newUser = [
            'username'=>$user->name,
            'create_at'=>time(),
            'admin'=>0,
            'email'=>$user->email,
            'token'=>$user->token,
            'password'=>encrypt(str_random(10)),
            'mode'=>'active',
            'category_id'=>get_option('user_default_category'),
        ];
        $ifUserExist = User::where('email',$newUser['email'])->first();

        if(empty($ifUserExist)){
            $insertUser = User::create($newUser);
            $request->session()->put('user',serialize($insertUser));
            return redirect('/user/profile');
        }else{
            $request->session()->put('user',serialize($ifUserExist->toArray()));
            return redirect('/user/dashboard');
        }
    }

    public function profile()
    {
        global $user;
        $userMetas = Usermeta::where('user_id',$user['id'])->pluck('value','option')->all();
        return view('user.pages.profile',['user'=>$user,'meta'=>$userMetas]);
    }
    public function profileStore(Request $request){
        global $user;
        User::find($user['id'])->update($request->all());
        return back();
    }
    public function profileMetaStore(Request $request)
    {
        global $user;
        Usermeta::updateOrNew($user['id'],$request->all());
        return back();
    }

    public function dashboard(){
        global $user;
        $userNotification = Notification::where(function($q){
            $q->where('recipent_type','all');
        })->orWhere(function ($q) use($user){
                $q->where('recipent_type','category')->where('recipent_list',$user['category_id']);
            })->orWhere(function ($q) use($user){
                $q->where('recipent_type','user')->where('recipent_list',$user['id']);
            })->limit(8)->orderBy('id','DESC')->get();
        $userBuy = Sell::with('content')->where('buyer_id',$user['id'])->where('mode','pay')->take(5)->orderBy('id','DESC')->get();
        $userBuyCount = Sell::with('content')->where('buyer_id',$user['id'])->where('mode','pay')->count();
        $userSell = Sell::with(['content'=>function($q){
            $q->with('metas');
        }])->where('user_id',$user['id'])->where('mode','pay')->get();
        $userMetas = Usermeta::where('user_id',$user['id'])->pluck('value','option')->all();
        $sell_rate = UserRate::where('mode','sellcount')->orderBy('id')->get();
        $total_icome = Transaction::where('user_id',$user['id'])->where('mode','deliver')->sum('price');
        $sell_rate_array = $sell_rate->toArray();
        $value = 0;
        $current_rate = [];
        $after_rate = [];
        foreach ($sell_rate_array as $index=>$sr){
            $min = explode(',',$sr['value'])[0];
            $max = explode(',',$sr['value'])[1];
            if($min<count($userSell) && $max>count($userSell)){
                $value = $index+1;
                $current_rate = $sr;
                if(isset($sell_rate_array[$index+1]))
                    $after_rate = $sell_rate_array[$index+1];
                else
                    $after_rate = [];
            }
        }

        $sell_count_today = 0;
        $sell_count_month = 0;

        foreach ($userSell as $us){
            if($us->create_at>strtotime("midnight")+12600){
                $sell_count_today++;
            }
            if($us->create_at>strtotime("-30 day")+12600){
                $sell_count_month++;
            }
        }


        for($i = 20;$i>=1;$i--){
            $timestamp = strtotime('-'.$i.' day') + 12600;
            $tenDay[$i]= (date('m/d', $timestamp));
            $sellDay[$i] = Sell::where('user_id',$user['id'])->where('mode','pay')->where('create_at','>',$timestamp)->where('create_at','<',$timestamp+86400)->count();
            $incomeDay[$i] = Transaction::where('user_id',$user['id'])->where('mode','deliver')->where('create_at','>',$timestamp)->where('create_at','<',$timestamp+86400)->sum('income');
        }

        //dd($tenDay);

        return view('user.dashboard',[
            'user'=>$user,
            'meta'=>$userMetas,
            'buyList'=>$userBuy,
            'sell_rate'=>$sell_rate,
            'value'=>$value,
            'current_rate'=>$current_rate,
            'userSellCount'=>count($userSell),
            'after_rate'=>$after_rate,
            'notifications'=>$userNotification,
            'userBuyCount'=>$userBuyCount,
            'total_income'=>$total_icome,
            'sell_count_today'=>$sell_count_today,
            'sell_count_month'=>$sell_count_month,
            'captionDay'=>$tenDay,
            'sellDay'=>$sellDay,
            'incomeDay'=>$incomeDay
        ]);
    }

    ## Show Profile For All Users ##
    public function profileView($id){
        global $user;
        $profile        = User::with('usermetas')->find($id);
        $videos         = Content::with('metas')->where('user_id',$id)->where('mode','publish')->get();
        $videosRate     = Content::with('metas')->where('user_id',$id)->where('mode','publish')->where('support_rate','>',0)->get();
        $channels       = Channel::where('mode','active')->where('user_id',$id)->get();
        $follow_count   = Follower::where('user_id',$id)->count();
        $articles       = Article::where('user_id',$id)->where('mode','publish')->orderBy('id','DESC')->get();
        $record         = Record::with(['category'])->where('user_id',$id)->where('mode','publish')->orderBy('id','DESC')->get();
        $menus          = contentMenu();
        $rates          = getRate($profile);
        if(!empty($user)){
            $follow = Follower::where('follower',$user['id'])->where('user_id',$id)->count();
        }else{
            $follow = 0;
        }

        $duration = 0;
        foreach ($videos as $viid){
            $meta = arrayToList($viid->metas,'option','value');
            if(isset($meta['duration']))
                $duration += $meta['duration'];
        }

        ## Retes
        $video_id_array = $videos->pluck('id')->toArray();
        $video_rate = ContentRate::whereIn('content_id',$video_id_array)->avg('rate');

        $article_id_array = $articles->pluck('id')->toArray();
        $article_rate = ArticleRate::whereIn('article_id',$article_id_array)->avg('rate');

        $sells_id_array = Sell::where('user_id',$id)->where('mode','pay')->get()->pluck('id');
        $sell_rate = SellRate::whereIn('sell_id',$sells_id_array)->avg('rate');

        if(empty($profile))
            return redirect('/');
        return view('view.profile.profile',['profile'=>$profile,'videos'=>$videos,'channels'=>$channels,'follow'=>$follow,'duration'=>$duration,'follow_count'=>$follow_count,'rates'=>$rates,'articles'=>$articles,'menus'=>$menus,'record'=>$record,'support_rate'=>round($videosRate->avg('support_rate'),1),'video_rate'=>round($video_rate,1),'article_rate'=>round($article_rate,1),'sell_rate'=>round($sell_rate,1)]);
    }
    public function follow($id){
        global $user;
        if(empty($user)){
            return redirect('/user');
        }

        $follow_count = Follower::where('user_id',$id)->where('follower',$user['id'])->count();
        if($follow_count>0){
            return back();
        }else{
            Follower::insert(['user_id'=>$id,'follower'=>$user['id']]);

            ## Notification Center
            sendNotification(0,['[u.name]'=>$user['name']],get_option('notification_template_request_follow'),'user',$id);

            return back();
        }
    }
    public function unfollow($id){
        global $user;
        if(empty($user)){
            return redirect('/user');
        }
        Follower::where('user_id',$id)->where('follower',$user['id'])->delete();
        return back();
    }
    public function profileRequestStore(Request $request){
        global $user;
        if($user == null)
            return redirect()->back()->with('msg',trans('main.login_request'));

        Requests::create([
            'user_id'=>$request->user_id,
            'requester_id'=>$user['id'],
            'title'=>$request->title,
            'description'=>$request->description,
            'category_id'=>$request->category_id,
            'mode'=>'draft',
            'create_at'=>time()
        ]);
        return redirect()->back()->with('msg',trans('main.req_success'));

    }

    ## Trip Mode ##
    public function tripModeDeactive(){
        global $user;
        setUserMeta($user['id'],'trip_mode','0');
        return back();
    }
    public function tripModeActive(Request $request){
        global $user;
        setUserMeta($user['id'],'trip_mode','1');
        setUserMeta($user['id'],'trip_mode_date',strtotime($request->trip_mode_date)+12600);
        setUserMeta($user['id'],'trip_mode_date_t',$request->trip_mode_date_t);
        return back();
    }

    ## Become Vendor ##
    public function becomeVendor(){
        return redirect('/user/ticket?type=become_vendor');
    }
}
