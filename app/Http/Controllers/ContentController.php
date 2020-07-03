<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Balance;
use App\Models\Blog;
use App\Models\BlogComments;
use App\Models\Channel;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentCategoryFilterTag;
use App\Models\ContentCategoryFilterTagRelation;
use App\Models\ContentComment;
use App\Models\ContentRate;
use App\Models\ContentSupport;
use App\Models\ContentVip;
use App\Models\Discount;
use App\Models\DiscountContent;
use App\Models\Favorite;
use App\Models\Follower;
use App\Models\Login;
use App\Models\Option;
use App\Models\Record;
use App\Models\RequestFans;
use App\Models\Requests;
use App\Models\RequestSuggestion;
use App\Models\Sell;
use App\Models\Transaction;
use App\Models\Usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\In;
use App\Models\User;
use App\Models\RecordFans;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use SoapClient;

class ContentController extends Controller
{
    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    ## Main Section ##
    public function main(){

        $new_content = Content::with('metas','user')->where('mode','publish')->limit(10)->orderBy('id','DESC')->get();
        $popular_content = Content::with('metas','user')->where('mode','publish')->limit(10)->orderBy('view','DESC')->get();
        $sell_content = Content::with('metas','user')->withCount('sells')->where('mode','publish')->limit(10)->orderBy('sells_count','DESC')->get();
        $vip_content = ContentVip::with('content.user')->where('mode','publish')->where('first_date','<',time())->where('last_date','>',time())->get();
        ## Get Blog Posts
        $blogPosts = Blog::where('mode','publish')->limit(get_option('main_page_blog_post_count',1))->orderBy('id','DESC')->get();## Get Blog Posts
        ## Get Articles
        $articlePosts = Article::where('mode','publish')->limit(get_option('main_page_article_post_count',1))->orderBy('id','DESC')->get();

        ## Get Best Users By Rate
        $user_Rate = User::with('usermetas')->where('mode','active')->where('admin','0')->orderBy('rate_count','DESC')->orderBy('rate_point','DESC')->limit(4)->get();
        ## Get Best Users By Content Count
        $user_content = User::withCount(array('contents'=>function($query){
            $query->where('mode','publish');
        }))->where('mode','active')->where('admin','0')->orderBy('contents_count','DESC')->limit(4)->get();
        ## Get Best Users By Popular
        $user_popular = User::where('mode','active')->where('admin','0')->limit(4)->orderBy('view','DESC')->get();

        ## Get Slider Container
        $slider_container = ContentVip::with(['content'=>function($q){
            $q->with(['metas','user']);
        }])
            ->where('first_date','<',time())
            ->where('last_date','>',time())
            ->where('mode','publish')
            ->where('type','slide')
            ->get();

        ## Get channels By Custom
        $channels['new'] = Channel::orderBy('id','DESC')->where('mode','active')->limit(4)->get();
        $channels['view'] = Channel::orderBy('view','DESC')->where('mode','active')->limit(4)->get();
        $channels['popular'] = Channel::with(['user'=>function($q){
            $q->withCount(['follow']);
        }])->where('mode','active')->limit(4)->get()->sortByDesc('user.followCount');

        return view('view.main',['sell_content'=>$sell_content,'vip_content'=>$vip_content,'new_content'=>$new_content,'popular_content'=>$popular_content,'blog_post'=>$blogPosts,'user_rate'=>$user_Rate,'user_content'=>$user_content,'user_popular'=>$user_popular,'slider_container'=>$slider_container,'channels'=>$channels,'article_post'=>$articlePosts]);
    }


    ## Category Section ##
    private static function orderPrice($a,$b){
        if($a['metas']['price'] == $b['metas']['price'])
            return 0;
        if($a['metas']['price'] < $b['metas']['price'])
            return 1;
        else
            return -1;
    }
    private static function orderCheap($a,$b){
        if($a['metas']['price'] == $b['metas']['price'])
            return 0;
        if($a['metas']['price'] > $b['metas']['price'])
            return 1;
        else
            return -1;
    }
    private static function orderSell($a,$b){
        if(count($a['sells']) == count($b['sells']))
            return 0;
        if(count($a['sells']) < count($b['sells']))
            return 1;
        else
            return -1;
    }
    private static function orderView($a,$b){
        if($a['view'] == $b['view'])
            return 0;
        if($a['view'] < $b['view'])
            return 1;
        else
            return -1;
    }
    private function pricing($array,$mode){
        if($mode == 'price'){
            foreach ($array as $index=>$a){
                if(!isset($a['metas']['price']) || $a['metas']['price'] == 0)
                    unset($array[$index]);
            }
        }
        if($mode == 'free'){
            foreach ($array as $index=>$a){
                if($a['metas']['price'] > 0)
                    unset($array[$index]);
            }
        }
        return $array;
    }
    private function course($array,$mode){
        if($mode == 'one'){
            foreach ($array as $index=>$a){
                if($a['parts_count'] > 1)
                    unset($array[$index]);
            }
        }
        if($mode == 'multi'){
            foreach ($array as $index=>$a){
                if($a['parts_count'] == 1)
                    unset($array[$index]);
            }
        }
        return $array;
    }
    private function off($array){
            foreach ($array as $index=>$a){
                if($a['metas']['price']==0) {
                    unset($array[$index]);
                }
                else{
                    $has_discount = DiscountContent::where('type','content')
                        ->where('mode','publish')
                        ->where('off_id',$a['id'])
                        ->where('first_date','<',time())
                        ->where('last_date','>',time())
                        ->count();
                    if($has_discount == 0)
                        unset($array[$index]);
                }
            }

        return $array;
    }
    private function filters($array,$filter){
            foreach ($array as $index=>$a){
                if(isset($a['metas']['filters'])) {
                    $filters_in = unserialize($a['metas']['filters']);
                    $c = array_intersect($filters_in, $filter);
                    if (count($c)==0)
                        unset($array[$index]);
                    }else{
                        //unset($array[$index]);
                    }
            }
        return $array;
    }
    public function category($id = null)
    {
        $order = Input::get('order');
        $price = Input::get('price');
        $course = Input::get('course');
        $off = Input::get('off');
        if(Input::get('filter')!=null) {
            $filters = array_unique(Input::get('filter'));
        }else{
            $filters = null;
        }
        $q = Input::get('q');
        $post_sell = Input::get('post-sell');
        $support = Input::get('support');

        $Category = ContentCategory::with(array('filters'=>function($q){$q->with('tags');}))->where('class',$id)->first();
        if($Category) {
            $vipContent = ContentVip::with(['content' => function ($q) {
                $q->with(['metas', 'sells', 'discount'])->where('mode', 'publish');
            }])
                ->where('first_date', '<', time())
                ->where('last_date', '>', time())
                ->where('type', 'category')
                ->where('mode', 'publish')
                ->where('category_id', $Category->id)
                ->orderBy('id', 'DESC')
                ->get();
        }else{
            $vipContent = [];
        }

        if(!$Category)
            $content = Content::with(['metas','sells','discount','user'])->withCount('parts')->where('mode','publish');
        else
            $content = Content::with(['metas','sells','discount','user'])->withCount('parts')->where('category_id',$Category->id)->where('mode','publish');

        if(isset($q) && $q!='')
            $content->where('title','LIKE','%'.$q.'%');

        if(isset($post_sell) && $post_sell == 1)
            $content->where('post','1');

        if(isset($support) && $support == 1)
            $content->where('support','1');

        if(isset($order) && $order == 'old')
            $content->orderBy('id');

        if(isset($order) && $order == 'new')
            $content->orderBy('id','DESC');

        ## Set For Course
        switch ($course){
            case 'one':
                $content->where('type','single');
                break;
            case 'multi':
                $content->where('type','course');
                break;
            case 'all':
                break;
            default:
                break;
        }

        $content = $content->get()->toArray();
        foreach ($content as $index=>$c){
            $content[$index]['metas'] = arrayToList($c['metas'],'option','value');
        }

        ## Most Sell
        $mostSellContent = $content;
        usort($mostSellContent,array($this,'orderSell'));
        $mostSellContent = array_slice($mostSellContent,0,3);


        ## Set For OrderBy
        switch($order){
            case 'price':
                usort($content,array($this,'orderPrice'));
                break;
            case 'cheap':
                usort($content,array($this,'orderCheap'));
                break;
            case 'sell':
                usort($content,array($this,'orderSell'));
                break;
            case 'popular':
                usort($content,array($this,'orderView'));
                break;
            default:
                break;
        }

        ## Set For Pricing
        switch ($price){
            case 'all':
                break;
            case 'free':
                $content = $this->pricing($content,'free');
                break;
            case 'price':
                $content = $this->pricing($content,'price');
                break;
            default:
                break;
        }

        ## Set For Off
        if($off == 1){
            $content = $this->off($content);
        }

        ## Set For Filters
        if($filters!=''){
            $content = $this->filters($content,$filters);
        }

        if($id != null)
            return view('view.category.category',['category'=>$Category,'contents'=>$content,'vip'=>$vipContent,'order'=>$order,'pricing'=>$price,'course'=>$course,'off'=>$off,'filters'=>$filters,'mostSell'=>$mostSellContent]);
        else
            return view('view.category.category_base',['category'=>$Category,'contents'=>$content,'vip'=>$vipContent,'order'=>$order,'pricing'=>$price,'course'=>$course,'off'=>$off,'filters'=>$filters,'mostSell'=>$mostSellContent]);
    }
    ######################

    ## Search Section ##
    public function search()
    {

        $search_type_title = trans('admin.search');
        $q = Input::get('q');
        if(!isset($_GET['type']) || $_GET['type']=='content_title') {
            $search_type_title = trans('admin.search_title');
            $content = Content::with(['metas', 'sells'])->withCount('parts')->where('mode', 'publish')->where('title', 'LIKE', '%' . $q . '%');
        }
        if(isset($_GET['type']) && $_GET['type']=='content_code') {
            $search_type_title = trans('admin.search_id');
            $content = Content::with(['metas', 'sells'])->withCount('parts')->where('mode', 'publish')->where('id', $q);
        }
        if(isset($_GET['type']) && $_GET['type']=='user_name') {
            $search_type_title = trans('admin.search_vendor');
            $content = User::with(['usermetas'])->where('mode', 'active')->where('name', 'LIKE', '%' . $q . '%');
        }
        if(isset($_GET['type']) && $_GET['type']=='content_filter') {
            $search_type_title = trans('admin.search_vendor');
            $tag_ids = ContentCategoryFilterTag::where('tag','LIKE','%'.$q.'%')->pluck('id');
            $content_ids = ContentCategoryFilterTagRelation::whereIn('tag_id',$tag_ids->toArray())->pluck('content_id');
            $content = Content::with(['metas','sells'])->withCount('parts')->where('mode','publish')->whereIn('id',array_unique($content_ids->toArray()));
        }



        $users = User::with('metas')->where('')->where('username','LIKE','%'.$q.'%');


        $content = $content->get()->toArray();
        if(!isset($_GET['type']) || (isset($_GET['type']) && $_GET['type']!='user_name'))
            foreach ($content as $index=>$c){
                $content[$index]['metas'] = arrayToList($c['metas'],'option','value');
            }
        else
            foreach ($content as $index=>$c){
                $content[$index]['metas'] = arrayToList($c['usermetas'],'option','value');
            }

        return view('view.search.search',['contents'=>$content,'users'=>$users,'search_title'=>$search_type_title]);
    }
    public function jsonSearch(){
        $q = Input::get('q');
        if(strlen($q)<5) {
            $content = ["title"=>" trans('admin.searching') $q..."];
            return $content;
        }

        $content = Content::where('mode', 'publish')->where('title', 'LIKE', '%' . $q . '%')->take(3)->select('id','title')->get();
        $tag_ids = ContentCategoryFilterTag::where('tag','LIKE','%'.$q.'%')->pluck('id');
        $content_ids = ContentCategoryFilterTagRelation::whereIn('tag_id',$tag_ids->toArray())->pluck('content_id');
        $content_filter = Content::where('mode','publish')->whereIn('id',array_unique($content_ids->toArray()))->select('id','title')->get();

        $content = array_merge($content->toArray(),$content_filter->toArray());
        foreach ($content as $index=>$con){
            $content[$index]['code'] = "(VT-".$con['id'].")";
        }
        return $content;

    }
    #################################

    ## Request Section ##
    public function request(){
        global $user;
        $list = Requests::with(['content','category','fans'=>function($q) use($user){
            $q->where('user_id',$user['id']);
        }])->withCount(['fans'])->where('mode','<>','draft')->orderBy('id','DESC');

        # Mode Filter
        if(Input::get('mode')!=null){
            switch (Input::get('mode')){
                case 'all':
                    break;
                case 'publish':
                    $list->where('mode','publish');
                    break;
                case 'accept':
                    $list->where('mode','accept');
                    break;
                default:
                    break;
            }
        }

        # Category Filter
        if(Input::get('cat')!=null)
            $list->whereIn('category_id',Input::get('cat'));

        # Search Filter
        if(Input::get('q')!=null)
            $list->where('title','LIKE','%'.Input::get('q').'%');

        return view('view.request.request',['list'=>$list->get()]);
    }
    public function requestFollow($id){
        global $user;
        if($user != null){
            RequestFans::create(['user_id'=>$user['id'],'request_id'=>$id]);
            return redirect()->back();
        }else{
            return redirect()->back()->with('msg',trans('admin.login_alert'));
        }
    }
    public function requestUnFollow($id){
        global $user;
        if($user != null){
            RequestFans::where('user_id',$user['id'])->where('request_id',$id)->delete();
            return redirect()->back();
        }else {
            return redirect()->back()->with('msg',trans('admin.login_alert'));
        }
    }
    public function requestSuggestion($id,$suggest){
        global $user;
        if($user == null){
            return redirect()->back()->with('msg',trans('admin.login_alert'));
        }else {
            $content = Content::where('title', 'LIKE', $suggest)->where('mode', 'publish')->first();
            if($content!=null) {
                RequestSuggestion::create([
                    'user_id' => $user['id'],
                    'request_id' => $id,
                    'content_id'=>$content->id
                ]);
                }
            return redirect()->back()->with('msg',trans('admin.request_sent_alert'));
        }

    }
    public function requestNew(){
        global $user;
        if($user == null)
            return redirect('/user?redirect=/request/new');

        return view('view.request.new');
    }
    public function requestStore(Request $request){
        global $user;
        if($user == null){
            return redirect('/user?redirect=/request/new');
        }else{
            Requests::create([
                'user_id'=>0,
                'requester_id'=>$user['id'],
                'category_id'=>$request->category_id,
                'title'=>$request->title,
                'description'=>$request->descriptoin,
                'mode'=>'draft',
                'create_at'=>time()
            ]);

            ## Notification Center
            sendNotification(0,['[r.title]'=>$request->title],get_option('notification_template_request_get'),'user',$user['id']);

            return redirect()->back()->with('msg',trans('admin.request_sent_approve'));
        }
    }

    ## Record Section ##
    public function record(){
        global $user;
        $list = Record::with(['content','category','fans'=>function($q) use($user){
            $q->where('user_id',$user['id']);
        }])->withCount(['fans'])->where('mode','publish');

        # Mode Filter
        if(Input::get('mode')!=null){
            switch (Input::get('mode')){
                case 'all':
                    break;
                case 'publish':
                    $list->where('content_id',null);
                    break;
                case 'accept':
                    $list->where('content_id','<>','0');
                    break;
                default:
                    break;
            }
        }

        # Category Filter
        if(Input::get('cat')!=null)
            $list->whereIn('category_id',Input::get('cat'));

        # Search Filter
        if(Input::get('q')!=null)
            $list->where('title','LIKE','%'.Input::get('q').'%');

        return view('view.record.record',['list'=>$list->get()]);
    }
    public function recordFollow($id){
        global $user;
        if($user != null){
            RecordFans::create(['user_id'=>$user['id'],'record_id'=>$id]);
            return redirect()->back();
        }else{
            return redirect()->back()->with('msg',trans('admin.login_alert'));
        }
    }
    public function recordUnFollow($id){
        global $user;
        if($user != null){
            RecordFans::where('user_id',$user['id'])->where('record_id',$id)->delete();
            return redirect()->back();
        }else {
            return redirect()->back()->with('msg',trans('admin.login_alert'));
        }
    }

    ## Blog Section ##
    public function blog()
    {
        $posts = Blog::where('mode','publish')->orderBy('id','DESC')->get();
        return view('view.blog.blog',['posts'=>$posts]);
    }
    public function blogPost($id)
    {
        $post = Blog::with(array('category','comments'=>function($query){
           $query->where('parent',0)->where('mode','publish')->with(array('childs'=>function($q){
               $q->where('mode','publish');
           }));
        }))->where('mode','publish')->find($id);
        return view('view.blog.post',['post'=>$post]);
    }
    public function blogPostMobile($id)
    {
        if(isset($_GET['type']) && $_GET['type'] == 'article') {
            $post = Article::where('mode','publish')->find($id);
        }else{
            $post = Blog::where('mode', 'publish')->find($id);
        }

        return view('app.post',['post'=>$post]);
    }
    public function blogCategory($id){
        $posts = Blog::where('mode','publish')->where('category_id',$id)->orderBy('id','DESC')->get();
        if($posts){
            return view('view.blog.blog',['posts'=>$posts]);
        }else{
            abort(404);
        }
    }
    public function blogPostCommentStore(Request $request)
    {
        global $user;
        if(!empty($user)){
            $request->request->add(['mode'=>'draft','create_at'=>time(),'user_id'=>$user['id'],'name'=>$user['name']]);
            BlogComments::insert($request->all());
        }
        return back();
    }
    public function blogTags($key){
        $posts = Blog::where('mode','publish')->where('tags','LIKE','%'.$key.'%')->orderBy('id','DESC')->get();
        return view('view.blog.blog',['posts'=>$posts]);
    }

    ## Product Section ##
    public function product($id)
    {
        error_reporting(0);
        global $user;
        $buy = Sell::where('buyer_id',$user['id'])->where('content_id',$id)->count();
        $product = Content::withCount(['comments'=>function($q){
            $q->where('mode','publish');
        }])->with(['discount','category'=>function($c) use($id){
            $c->with(['discount'=>function($dc) use($id){
                $dc->where('off_id',Content::find($id)->category->id);
            }]);
        },'rates','user'=>function($u){
            $u->with(['usermetas','point','contents'=>function($cQuery){
                $cQuery->where('mode','publish')->limit(3);
            }]);
        },'metas','parts'=>function($query){
            $query->where('mode','publish')->orderBy('sort');
        },'favorite'=>function($fquery) use ($user){
            $fquery->where('user_id',$user['id']);
        },'comments'=>function($ccquery) use($id){
            $ccquery->where('mode','publish')->with(['user'=>function($uquery) use($id){
                $uquery->with(['category','usermetas'])->withCount(['buys'=>function($buysq) use($id){
                    $buysq->where('content_id',$id);
                },'contents'=>function($contentq) use($id){
                    $contentq->where('id',$id);
                }]);
            },'childs'=>function($cccquery) use($id) {
                $cccquery->where('mode', 'publish')->with(['user'=>function($cuquery) use($id){
                    $cuquery->with(['category','usermetas'])->withCount(['buys'=>function($buysq) use($id){
                        $buysq->where('content_id',$id);
                    },'contents'=>function($contentq) use($id){
                        $contentq->where('id',$id);
                    }]);
                }]);
            }]);
        },'supports'=>function($q) use ($user){
            $q->with(['user.usermetas','supporter.usermetas','sender.usermetas'])->where('sender_id',$user['id'])->where('mode','publish')->orderBy('id','DESC');
        }])->where(function ($where){
            $where->where('mode','publish');
        })->find($id);

        if(!$product)
            return abort(404);

        ## Update View
        $product->increment('view');

        if($product->price == 0 && $user)
            $buy = 1;

        $subscribe = false;
        if(isset($buy->tupe) && $buy->type == 'subscribe' && $buy->remain_time - time()) {
            $buy        = 0;
            $subscribe  = true;
        }

        if(!$product)
            return abort(404);

        $meta = arrayToList($product->metas,'option','value');
        $parts = $product->parts->toArray();
        $rates = getRate($product->user->toArray());



        ## Get Related Content ##
        $relatedCat = $product->category_id;
        $relatedContent = Content::with(['metas'])->where('category_id',$relatedCat)->where('id','<>',$product->id)->where('mode','publish')->limit(3)->inRandomOrder()->get();


        ## Get PreCourse Content ##
        if(isset($meta['precourse']))
            $preCourseIDs = explode(',',rtrim($meta['precourse'],','));
        else
            $preCourseIDs = [];
        $preCousreContent = Content::where('mode','publish')->whereIn('id',$preCourseIDs)->get();


        if(!cookie('cv'.$id)) {
            $product->increment('view');
            setcookie('cv'.$id,'1');
        }

        return view('view.product.product',['product'=>$product,'meta'=>$meta,'parts'=>$parts,'rates'=>$rates,'buy'=>$buy,'related'=>$relatedContent,'precourse'=>$preCousreContent,'subscribe'=>$subscribe]);
    }
    public function productPart($id,$pid)
    {
        error_reporting(0);
        global $user;
        if(!$user)
            return redirect('/product/'.$id)->with('msg',trans('admin.login_to_play_video'));

        $buy = Sell::where('buyer_id',$user['id'])->where('content_id',$id)->count();
        $product = Content::withCount(['comments'=>function($q){
            $q->where('mode','publish');
        }])->with(['discount','category'=>function($c) use($id){
            $c->with(['discount'=>function($dc) use($id){
                $dc->where('off_id',Content::find($id)->category->id);
            }]);
        },'rates','user'=>function($u){
            $u->with(['usermetas','point','contents'=>function($cQuery){
                $cQuery->where('mode','publish')->limit(3);
            }]);
        },'metas','parts'=>function($query){
            $query->where('mode','publish')->orderBy('sort');
        },'favorite'=>function($fquery) use ($user){
            $fquery->where('user_id',$user['id']);
        },'comments'=>function($ccquery) use($id){
            $ccquery->where('mode','publish')->with(['user'=>function($uquery) use($id){
                $uquery->with(['category','usermetas'])->withCount(['buys'=>function($buysq) use($id){
                    $buysq->where('content_id',$id);
                },'contents'=>function($contentq) use($id){
                    $contentq->where('id',$id);
                }]);
            },'childs'=>function($cccquery) use($id) {
                $cccquery->where('mode', 'publish')->with(['user'=>function($cuquery) use($id){
                    $cuquery->with(['category','usermetas'])->withCount(['buys'=>function($buysq) use($id){
                        $buysq->where('content_id',$id);
                    },'contents'=>function($contentq) use($id){
                        $contentq->where('id',$id);
                    }]);
                }]);
            }]);
        },'supports'=>function($q) use ($user){
            $q->with(['user.usermetas','supporter.usermetas','sender.usermetas'])->where('sender_id',$user['id'])->where('mode','publish')->orderBy('id','DESC');
        }])->where(function ($where){
            $where->where('mode','publish');
        })->find($id);

        if(!$product)
            return abort(404);

        ## Update View
        $product->increment('view');

        if($product->price == 0 && $user)
            $buy = 1;

        if(!$product)
            return abort(404);

        $meta = arrayToList($product->metas,'option','value');
        $parts = $product->parts->toArray();
        $rates = getRate($product->user->toArray());



        ## Get Related Content ##
        $relatedCat = $product->category_id;
        $relatedContent = Content::with(['metas'])->where('category_id',$relatedCat)->where('id','<>',$product->id)->where('mode','publish')->limit(3)->inRandomOrder()->get();


        ## Get PreCourse Content ##
        if(isset($meta['precourse']))
            $preCourseIDs = explode(',',rtrim($meta['precourse'],','));
        else
            $preCourseIDs = [];
        $preCousreContent = Content::where('mode','publish')->whereIn('id',$preCourseIDs)->get();


        if(!cookie('cv'.$id)) {
            $product->increment('view');
            setcookie('cv'.$id,'1');
        }

        return view('view.product.product',['product'=>$product,'meta'=>$meta,'parts'=>$parts,'rates'=>$rates,'buy'=>$buy,'related'=>$relatedContent,'precourse'=>$preCousreContent,'partVideo'=>'/video/stream/'.$pid]);
    }
    public function productFav($id){
        global $user;
        if($user){
            Favorite::insert(['content_id'=>$id,'user_id'=>$user['id']]);
        }
        return back();
    }
    public function productUnFav($id){
        global $user;
        if($user){
            Favorite::where('content_id',$id)->where('user_id',$user['id'])->delete();
        }
        return back();
    }
    public function productCommentStore($id,Request $request){
        global $user;
        if($user == null)
            return redirect()->back()->with('msg',trans('admin.login_to_comment'));
        ContentComment::create([
            'comment'=>$request->comment,
            'user_id'=>$user['id'],
            'create_at'=>time(),
            'name'=>$user['name'],
            'content_id'=>$id,
            'parent'=>$request->parent,
            'mode'=>'draft'
        ]);

        return redirect()->back()->with('msg',trans('admin.comment_success'));
    }
    public function productSupportStore(Request $request){
        global $user;
        if($user == null) {
            return redirect()->back()->with('msg', trans('admin.login_alert'));
        }

        $buy = Sell::where('buyer_id',$user['id'])->where('content_id',$request->content_id)->first();
        if(isset($buy)){
            ContentSupport::create([
                'comment'=>$request->comment,
                'user_id'=>$user['id'],
                'create_at'=>time(),
                'name'=>$user['name'],
                'content_id'=>$request->content_id,
                'mode'=>'draft',
                'supporter_id'=>$buy->user_id,
                'sender_id'=>$user['id']
            ]);
            return redirect()->back()->with('msg',trans('admin.support_success'));
        }else {
            return redirect()->back()->with('msg',trans('admin.support_failed'));
        }
    }
    public function productSupportRate($id,$rate){
        global $user;
        $support = ContentSupport::where('sender_id',$user['id'])->find($id);
        if(!$support)
            return 0;

        if($rate>5 or $rate<0)
            return 0;

        ## Update Support Message
        $support->update(['rate'=>$rate]);

        ## Update Content Rate
        $avg_rate = ContentSupport::where('content_id',$support->content_id)->whereRaw('user_id=supporter_id')->get()->avg('rate');
        Content::find($support->content_id)->update(['support_rate'=>$avg_rate]);

        return 1;
    }
    public function productRate($id,$rate){
        global $user;
        if($rate>5 || $rate<0){
            return redirect()->back()->with('msg',trans('admin.rate_alert'));
        }
        if($user == null){
            return redirect()->back()->with('msg',trans('admin.rate_login'));
        }
        $content = Content::with('metas')->find($id);
        $contentMeta = $content->metas->pluck('value','option');
        if($contentMeta['price']>0){
            $buy = Sell::where('buyer_id',$user['id'])->where('content_id',$id)->count();
            if($buy>0){
                ContentRate::updateOrCreate(
                    [
                        'content_id'=>$id,
                        'user_id'=>$user['id']
                    ],
                    [
                        'content_id'=>$id,
                        'rate'=>$rate,
                        'user_id'=>$user['id']
                    ]
                );
                return redirect()->back()->with('msg',trans('admin.rating_success'));
            }else {
                return redirect()->back()->with('msg',trans('admin.rating_students'));
            }
        }else{
            ContentRate::updateOrCreate(
                [
                    'content_id'=>$id,
                    'user_id'=>$user['id']
                ],
                [
                    'content_id'=>$id,
                    'rate'=>$rate,
                    'user_id'=>$user['id']
                ]
            );
            return redirect()->back()->with('msg',trans('admin.rating_success'));
        }

    }

    ## Gift ##
    public function giftCheker($code){
        $gift = Discount::where('code',$code)->where('create_at','<',time())->where('expire_at','>',time())->first();
        if($gift) {
            session(['gift'=>$gift->id]);
            return $gift;
        }else
            return 0;
    }

    ## Chanel Section
    public function chanel($username){
        global $user;
        $chanel = Channel::with(['contents'=>function($q){
            $q->with(['content'=>function($c){
                $c->with('metas')->where('mode','publish');
            }]);
        }])->withCount(['contents'])->where('username',$username)->where('mode','active')->first();
        if(!$chanel)
            return abort(404);

        if($user)
            $follow = Follower::where('follower',$chanel->user_id)->where('user_id',$user['id'])->where('type','chanel')->count();
        else
            $follow = 0;

        $duration = 0;
        foreach ($chanel->contents as $c){
            $meta = arrayToList($c->content->metas,'option','value');
            if(isset($meta['duration']))
                $duration = $duration + $meta['duration'];
        }

        return view('view.chanel.chanel',['chanel'=>$chanel,'follow'=>$follow, 'duration' => $duration ]);
    }
    public function chanelFollow($id){
        global $user;
        Follower::create([
            'user_id'   => $user['id'],
            'type'      => 'chanel',
            'follower'  => $id
        ]);
        return back();
    }
    public function chanelUnFollow($id){
        global $user;
        Follower::where('user_id',$user['id'])->where('type','chanel')->where('follower',$id)->delete();
        return back();
    }

    ## Article Section
    public function articleList(){
        $order = Input::get('order');
        $cats = Input::get('cat');

        $Articles = Article::with(['user','rate'])->withCount('rate')->where('mode','publish');

        if($cats != null){
            $Articles->whereIn('cat_id',$cats);
        }

        switch ($order){
            case 'old':
                $Articles->orderBy('id');
                break;
            case 'new':
                $Articles->orderBy('id','DESC');
                break;
            case 'view':
                $Articles->orderBy('view','DESC');
                break;
            case 'popular':
                $Articles->orderBy('rate_count','DESC');
                break;
            default:
                $Articles->orderBy('id','DESC');
        }

        $Category = ContentCategory::where('parent_id','0')->get();
        return view('view.article.list',['posts'=>$Articles->get(),'category'=>$Category,'order'=>$order,'cats'=>$cats]);
    }
    public function articleItem($id){

        $post = Article::with(['category','user.usermetas'])->where('mode','publish')->find($id);

        if(!$post)
            abort(404);

        if($post->user!=null)
            $rates = getRate($post->user->toArray());
        else
            $rates = [];

        $userContent = Content::with(['metas'])->where('mode','publish')->where('user_id',$post->user_id)->limit(4)->inRandomOrder()->get();
        $relContent = Content::with(['metas'])->where('mode','publish')->where('category_id',$post->cat_id)->limit(4)->inRandomOrder()->get();
        $post->increment('view');
        return view('view.article.article',['post'=>$post,'rates'=>$rates,'userContent'=>$userContent,'relContent'=>$relContent]);
    }


    ## Page Section ##
    public function page($key){
        return view('view.blog.page',['content'=>get_option($key,trans('admin.content_not_found'))]);
    }

    ## Subscribe
    public function productSubscribe($id, $type, $payMode, Request $request)
    {
        global $user;
        if (!$user)
            return Redirect::to('/user?redirect=/product/' . $id);

        $content = Content::with('metas')->where('mode', 'publish')->find($id);
        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($type == 3) {
            $remain = 3 * 30 * 86400;
            $Amount = $content->price_3;
        }
        if ($type == 6) {
            $remain = 6 * 30 * 86400;
            $Amount = $content->price_6;
        }
        if ($type == 9) {
            $remain = 9 * 30 * 86400;
            $Amount = $content->price_9;
        }
        if ($type == 12) {
            $remain = 12 * 30 * 86400;
            $Amount = $content->price_12;
        }

        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];
        if ($payMode == 'paypal') {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($content->title)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($Amount_pay);
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($Amount_pay);
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Purchase Product');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(url('/') . '/bank/paypal/status')
            ->setCancelUrl(url('/') . '/bank/paypal/cancel/' . $id);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return Redirect::route('paywithpaypal');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::route('paywithpaypal');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        $ids = $payment->getId();
        \Session::put('paypal_payment_id', $ids);
        Transaction::insert([
            'buyer_id' => $user['id'],
            'user_id' => $content->user_id,
            'content_id' => $content->id,
            'price' => $Amount_pay,
            'price_content' => $Amount,
            'mode' => 'pending',
            'create_at' => time(),
            'bank' => 'paypal',
            'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
            'authority' => $ids,
            'type' => 'subscribe',
            'remain_time' => time() + $remain,
            'type' => 'subscribe'
        ]);
        /** add payment ID to session **/
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('paywithpaypal');
    }
        if($payMode == 'credit'){
            if($user['credit']-$Amount_pay<0) {
                return redirect('/product/' . $id)->with('msg', trans('admin.no_charge_error'));
            }else{
                $seller = User::with('category')->find($content->user_id);
                $transaction = Transaction::create([
                    'buyer_id'      => $user['id'],
                    'user_id'       => $content->user_id,
                    'content_id'    => $content->id,
                    'price'         => $Amount_pay,
                    'price_content' => $Amount,
                    'mode'          => 'deliver',
                    'create_at'     => time(),
                    'bank'          => 'credit',
                    'authority'     => '000',
                    'income'        => $Amount_pay - (($site_income/100)*$Amount_pay),
                    'type'          => 'subscribe',
                    'remain_time'   => time() + $remain
                ]);
                Sell::insert([
                    'user_id'       => $content->user_id,
                    'buyer_id'      => $user['id'],
                    'content_id'    => $content->id,
                    'type'          => 'subscribe',
                    'create_at'     => time(),
                    'mode'          => 'pay',
                    'transaction_id'=> $transaction->id,
                    'remain_time'   => time() + $remain
                ]);

                $seller->update(['income'=>$seller->income+((100-$site_income)/100)*$Amount_pay]);
                $buyer = User::find($user['id']);
                $buyer->update(['credit'=>$user['credit']-$Amount_pay]);

                Balance::create([
                    'title'=>trans('admin.item_purchased').$content->title,
                    'description'=>trans('admin.item_purchased_desc'),
                    'type'=>'minus',
                    'price'=>$Amount_pay,
                    'mode'=>'auto',
                    'user_id'=>$buyer->id,
                    'exporter_id'=>0,
                    'create_at'=>time()
                ]);
                Balance::create([
                    'title'=>trans('admin.item_sold').$content->title,
                    'description'=>trans('admin.item_sold_desc'),
                    'type'=>'add',
                    'price'=>((100-$site_income)/100)*$Amount_pay,
                    'mode'=>'auto',
                    'user_id'=>$seller->id,
                    'exporter_id'=>0,
                    'create_at'=>time()
                ]);
                Balance::create([
                    'title'=>trans('admin.item_profit').$content->title,
                    'description'=>trans('admin.item_profit_desc').$buyer->username,
                    'type'=>'add',
                    'price'=>($site_income/100)*$Amount_pay,
                    'mode'=>'auto',
                    'user_id'=>0,
                    'exporter_id'=>0,
                    'create_at'=>time()
                ]);

                ## Notification Center
                $product = Content::find($transaction->content_id);
                sendNotification(0,['[c.title]'=>$product->title],get_option('notification_template_buy_new'),'user',$buyer->id);
                return redirect()->back()->with('msg',trans('admin.item_purchased_success'));
            }
        }
    }

    ## Usage
    public function usage($product_id, $user_id){
        $New = Usage::create([
            'user_id'       => $user_id,
            'product_id'    => $product_id,
            'created_at_sh' => time(),
            'updated_at_sh' => time()
        ]);
        return $New;
    }

    ## Login
    public function login($user){
        $New = Login::create([
            'user_id'       => $user,
            'created_at_sh' => time(),
            'updated_at_sh' => time()
        ]);
        return $New;
    }

}
