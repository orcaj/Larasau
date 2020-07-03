<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Channel;
use App\Models\ChannelRequest;
use App\Models\Event;
use App\Models\Usercategories;
use App\Models\Usermeta;
use App\Models\UserRate;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use App\Models\UserRateRelation;
use Maatwebsite\Excel\Facades\Excel;

class userController extends Controller
{

    public function login(Request $request){
        if($request->session()->has('Admin') ){
            return redirect('/admin/user/lists');
        }
        return view('admin.login');
    }
    public function dologin(Request $request){
        $username = $request->username;
        $password = $request->password;
        $admin = User::where('username',$username)->where('admin','1')->where('mode','active')->first();
        if($admin && $de = decrypt($admin->password) == $password){
            $request->session()->put('Admin',serialize($admin->toArray()));
            $user = User::find($admin->id);
            $user->last_view = time();
            $user->save();

            return redirect('/admin/report/user');
        }else{
            $request->session()->flash('Error','notfonud');
            return redirect('/admin/login');
        }
    }
    public function logout()
    {
        Session::flush();
        return view('admin.login');
    }
    public function remember()
    {
        return view('admin.remember');
    }

    ## User Section ##
    public function lists()
    {
        $fdate = strtotime(Input::get('fsdate'));
        $ldate = strtotime(Input::get('lsdate'));
        $userList = User::with('category')->withCount('contents','sells','buys')->where('admin','0');

        if($fdate>12601)
            $userList->where('create_at','>',$fdate);
        if($ldate>12601)
            $userList->where('create_at','<',$ldate);

        if(Input::get('order')!=null) {
            switch (Input::get('order')){
                case 'sella':
                    $userList->orderBy('sells_count');
                    break;
                case 'selld':
                    $userList->orderBy('sells_count','DESC');
                    break;
                case 'buya':
                    $userList->orderBy('buys_count');
                    break;
                case 'buyd':
                    $userList->orderBy('buys_count','DESC');
                    break;
                case 'contenta':
                    $userList->orderBy('contents_count');
                    break;
                case 'contentd':
                    $userList->orderBy('contents_count','DESC');
                    break;
                case 'datea':
                    $userList->orderBy('create_at');
                    break;
                case 'seller':
                    $userList->has('sells','>',0)->with(['sells','usermetas'=>function($q){
                        $q->pluck('value','option');
                    }]);
                    break;

            }
        }
        else
            $userList->orderBy('id','DESC');

        $userList = $userList->paginate(15);
        return view('admin.user.list',array('users'=>$userList));
    }
    public function item($id)
    {
        $userCategory = Usercategories::all();
        $user = User::where('id',$id)->first();
        $userMetas = Usermeta::where('user_id',$id)->pluck('value','option')->all();
        $mrate = UserRateRelation::with('rate')->where('user_id',$id)->get();
        $getrate = getRate($user);
        $lists = UserRate::orderBy('mode')->get();
        //dd($lists);
        return view('admin.user.item',array('user'=>$user,'category'=>$userCategory,'meta'=>$userMetas,'lists'=>$lists,'mrates'=>$mrate,'getrate'=>$getrate));
    }
    public function edit($id,Request $request){
        $request->request->remove('block_date');
        Usermeta::updateOrNew($id,['blockDate'=>toTimestamp($request->blockDate)]);
        $request->request->remove('blockDate');
        $uUser = User::with('category')->find($id);

        ## Notification Center
        if($uUser->category_id != $request->category_id && isset($uUser->category->title)) {
            sendNotification(0,['[u.username]'=>$uUser->username,'[u.c.title]'=>$uUser->category->title],get_option('notification_template_change_group'),'user',$uUser->id);
        }

        $uUser->update($request->all());
        return back();
    }
    public function delete($id){
        User::where('id',$id)->delete();
        return back();
    }
    public function editprofile($id,Request $request){
        Usermeta::updateOrNew($id,$request->all());
        return back();
    }
    public function rateSection($id,Request $request){
        UserRateRelation::updateOrNew($id,[$request->rate]);
        $userRate = UserRate::find($request->rate);

        $userR = User::find($id);
        ## Notification Center
        sendNotification(0,['[u.name]'=>$userR->name,'[u.username]'=>$userR->username],get_option('notification_template_get_medal'),'user',$id);

        if($userRate->gift>0){
            Balance::create([
                'title'=>trans('admin.user_rate_badge_gift'),
                'description'=>trans('admin.user_rate_new_badge_gift').$userRate->description,
                'type'=>'add',
                'price'=>$userRate->gift,
                'mode'=>'auto',
                'user_id'=>$id,
                'exporter_id'=>0,
                'create_at'=>time()
            ]);
            $userUp = User::find($id);
            $userUp->update(['credit'=>$userUp->credit+$userRate->gift]);
        }
        return redirect(URL::previous().'#rate');
    }
    public function rateSectionDelete($id){
        $userRR = UserRateRelation::find($id);
        $userM = UserRate::find($userRR->rate_id);
        $userR = User::find($userRR->user_id);
        ## Notification Center
        sendNotification(0,['[u.name]'=>$userR->name,'[u.username]'=>$userR->username,'[u.m.title]'=>$userM->description],get_option('notification_template_delete_medal'),'user',$userR->id);

        $userRR->delete();
        return redirect(URL::previous().'#rate');
    }

    ## Vendor Section ##
    public function vendors(){
        $list = User::where('admin',0)->where('vendor',1);

        return view('admin.user.vendors',['users'=>$list->paginate(15)]);
    }


    ## Category Section ##
    public function category()
    {
        $lists = Usercategories::withCount('users')->get();
       return view('admin.user.categroy',array('lists'=>$lists));
    }
    public function categoryEdit($id)
    {
        $lists = Usercategories::withCount('users')->get();
        $edit = Usercategories::find($id);
        return view('admin.user.categroyedit',array('lists'=>$lists,'edit'=>$edit));
    }
    public function categoryStore(Request $request){
        Usercategories::create($request->all());
        return back();
    }
    public function categoryEditStore($id,Request $request){
        Usercategories::find($id)->update($request->all());
        return back();
    }
    public function incategory($id)
    {
        $category = Usercategories::where('id',$id)->first();
        $userList = User::with('category','contents','sells','buys')->where('admin','0')->where('category_id',$id)->get();
        return view('admin.user.incategory',array('users'=>$userList,'category'=>$category));
    }
    public function categoryDelete($id){
        Usercategories::find($id)->delete();
        return back()->with('msg', trans('main.delete'));
    }

    ## Rate Section
    public function rate()
    {
        $lists = UserRate::all();
        return view('admin.user.rate',array('lists'=>$lists));
    }
    public function ratestore(Request $request)
    {
        if($request->edit == '') {
            $newRate = new UserRate;
            $newRate->description = $request->description;
            $newRate->image = $request->image;
            $newRate->mode = $request->mode;
            $newRate->gift = $request->price;
            $newRate->commision = $request->commision;
            $newRate->value = $request->start . ',' . $request->end;
            $newRate->save();
        }else{
            $rate = UserRate::find($request->edit);
            $rate->description = $request->description;
            $rate->image = $request->image;
            $rate->mode = $request->mode;
            $rate->gift = $request->price;
            $rate->commision = $request->commision;
            $rate->value = $request->start . ',' . $request->end;
            $rate->save();
        }
        return redirect(url()->previous().'#'.$request->mode);
    }
    public function ratedelete($id,$tag)
    {
        $deleteItem = UserRate::find($id);
        $deleteItem->delete();
        return redirect(url()->previous().'#'.$tag);
    }
    public function rateedit($id,$tag)
    {
        $lists = UserRate::all();
        $item = UserRate::where('id',$id)->first();
        $item->start = explode(',',$item->value)[0];
        $item->end = explode(',',$item->value)[1];
        return view('admin.user.rate',array('lists'=>$lists,'item'=>$item,'tag'=>$tag));
    }

    ## Channel Section
    public function channelList()
    {
        $channels = Channel::with(['user'])->withCount(['contents'])->orderBy('id','DESC')->get();
        return view('admin.user.channels',['channels'=>$channels]);
    }
    public function channelDelete($id){
        Channel::where('id',$id)->delete();
        return back();
    }
    public function channelEdit($id)
    {
        $item = Channel::find($id);
        return view('admin.user.channeledit',['edit'=>$item]);
    }
    public function channelStore($id,Request $request)
    {
        $channel = Channel::find($id);
        $channel->update($request->all());
        return back();
    }
    public function channelRequest()
    {
        $lists = ChannelRequest::orderBy('id','DESC')->get();
        return view('admin.user.channelrequest',['lists'=>$lists]);
    }
    public function channelRequestDelete($id)
    {
        ChannelRequest::find($id)->delete();
        return back();
    }
    public function channelRequestDraft($id)
    {
        ChannelRequest::find($id)->update(['mode'=>'draft']);
        return back();
    }
    public function channelRequestPublish($id)
    {
        ChannelRequest::find($id)->update(['mode'=>'publish']);
        return back();
    }

    ## Admin Login Like User
    public function userLogin($id,Request $request)
    {
        $admin = User::find($id);
        $request->session()->put('user',serialize($admin->toArray()));
        Event::create([
            'user_id'   => $admin->id,
            'type'      => 'Admin Login',
            'ip'        => $request->ip()
        ]);
        return redirect('/user');
    }

    ## Excel
    public function channelExcel(){
            $lists = Channel::with(['user'])->withCount(['contents'=>function($q){
                $q->where('option','channel');
            }])->get();
        Excel::create(trans('admin.channels_list'), function($excel) use($lists){
            $excel->sheet('Sheetname', function($sheet) use($lists){
                $sheet->freezeFirstRow();
                $sheet->setAutoSize(true);
                $sheet->cell('A1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('B1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('C1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('D1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('E1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('F1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('G1', function($cell) {$cell->setBackground('#FFAB25');});
                $sheet->cell('H1', function($cell) {$cell->setBackground('#FFAB25');});

                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Tahoma',
                        'size'      =>  12,
                        'text-align'  => 'center'
                    )));
                $sheet->appendRow(array(
                    trans('admin.channel_title'),
                    trans('admin.channel_id'),
                    trans('admin.creator'),
                    trans('admin.verification_status'),
                    trans('admin.contents'),
                    trans('admin.th_status'),
                    trans('admin.views'),
                ));
                foreach ($lists as $item) {
                    if($item->formal == 'ok')
                        $formal = trans('admin.verified');
                    else
                        $formal = trans('admin.not_verified');

                    if($item->mode == 'active')
                        $mode = trans('admin.active');
                    else
                        $mode = trans('admin.disabled');


                    $sheet->appendRow(array(
                        $item->title,
                        $item->username,
                        $item->user->username,
                        $formal,
                        $item->contents_count,
                        $mode,
                        $item->view
                    ));
                }
            });
        })->download('xls');
    }

    ## Seller
    public function seller(){
        $users = User::where('mode','active')
            ->has('sells')
            ->whereHas('usermetas',function($q){
                $q->where('option','seller_apply')->where('value',1);
            })
            ->get();
        return view('admin.user.seller',['users'=>$users]);
    }

    ## Events
    public function event($id){
        $list = Event::where('user_id', $id)->orderBy('id','DESC');
        return view('admin.user.event',['list'=>$list->paginate(15)]);
    }

}
