<?php

function returnCaptibiliy($array){

    if(!$array) return;
    $array = unserialize($array);
    $string_array = implode(', ',$array);
    return $string_array;
}

function arrayToList($array,$key,$val){
    if(empty($array) OR count($array) == 0)
        return;
    foreach ($array as $a){
        $result[$a[$key]] = $a[$val];
    }
    return $result;
}

function checkedInArray($value,$array,$serialize = false){
    if($serialize == true) $array = unserialize($array);
    if(in_array($value,$array))
        echo 'checked="checked"';
}

function checkedInObject($value,$key,$object){
    if((is_array($object) || is_object($object)) && count($object)>0) {
        foreach ($object as $obj) {
            if ($obj->$key == $value) {
                echo 'checked="checked"';
                return;
            }
        }
    }else{
        return null;
    }
}

function checkedIn($value,$key){
    if($value = $key)
        echo 'checked="checked"';
}

function get_option($option,$default = null){
    if($result = \App\Models\Option::where('option',$option)->value('value'))
        return $result;
    else
        return $default;
}

function del_option($option){
    \App\Models\Option::where('option',$option)->delete();
}

function set_option($option,$value,$mode = 'normal'){
        \App\Models\Option::updateOrCreate(
            ['option'=>$option],
            ['value'=>$value,'mode'=>$mode]
        );
}

function currency(){
    return get_option('currency','USD');
}

function currencySign(){
    switch (get_option('currency')){
        case 'USD':
            return '$';
            break;
        case 'EUR':
            return '€';
            break;
        case 'JPY':
        case 'CNY':
            return '¥';
            break;
        case 'AED':
            return 'د.إ';
            break;
        case 'SAR':
            return 'ر.س';
            break;
        case 'KRW':
            return '₩';
            break;
        case 'INR':
            return '₹';
            break;
        case 'RUB':
            return '₽';
            break;
		case 'Lek':
            return 'Lek';
            break;
		case 'AFN':
            return '؋';
            break;
		case 'ARS':
            return '$';
            break;
		case 'AWG':
            return 'ƒ';
            break;
		case 'AUD':
            return '$';
            break;
		case 'AZN':
            return '₼';
            break;
		case 'BSD':
            return '$';
            break;
		case 'BBD':
            return '$';
            break;
		case 'BYN':
            return 'Br';
            break;
		case 'BZD':
            return 'BZ$';
            break;
		case 'BMD':
            return '$';
            break;
		case 'BOB':
            return '$b';
            break;
		case 'BAM':
            return 'KM';
            break;
		case 'BWP':
            return 'P';
            break;
		case 'BGN':
            return 'лв';
            break;
		case 'BRL':
            return 'R$';
            break;
		case 'BND':
            return '$';
            break;
		case 'COP':
            return '$';
            break;
		case 'CRC':
            return '₡';
            break;
		case 'CZK':
            return 'Kč';
            break;
		case 'CUP':
            return '₱';
            break;
		case 'DKK':
            return 'kr';
            break;
		case 'DOP':
            return 'RD$';
            break;
		case 'XCD':
            return '$';
            break;
		case 'EGP':
            return '£';
            break;
		case 'GTQ':
            return 'Q';
            break;
		case 'HKD':
            return '$';
            break;
		case 'HUF':
            return 'Ft';
            break;
		case 'IDR':
            return 'Rp';
            break;
		case 'IRR':
            return '﷼';
            break;
		case 'ILS':
            return '₪';
            break;
		case 'LBP':
            return '£';
            break;
		case 'MYR':
            return 'RM';
            break;
		case 'NGN':
            return '₦';
            break;
		case 'NOK':
            return 'kr';
            break;
		case 'OMR':
            return '﷼';
            break;
		case 'PKR':
            return '₨';
            break;
		case 'PHP':
            return '₱';
            break;
		case 'PLN':
            return 'zł';
            break;
		case 'RON':
            return 'lei';
            break;
		case 'ZAR':
            return 'R';
            break;
		case 'LKR':
            return '₨';
            break;
		case 'SEK':
            return 'kr';
            break;
		case 'CHF':
            return 'CHF';
            break;
		case 'THB':
            return '฿';
            break;
		case 'TRY':
            return '₺';
            break;
		case 'UAH':
            return '₴';
            break;
		case 'GBP':
            return '£';
            break;
		case 'VND':
            return '₫';
            break;
		case 'TWD':
            return 'NT$';
            break;
		case 'UZS':
            return 'лв';
            break;
        default:
            return '$';
    }

    return '$';
}

function get_user_meta($user_id,$meta_key,$default = null){
    $meta = \App\Models\Usermeta::where('user_id',$user_id)->where('option',$meta_key)->first();
    if($meta)
        return $meta->value;
    else
        return $default;
}

function all_option($mode = 'object'){
    if($mode == 'object')
   return \App\Models\Option::lists('value','option');
    else
    {
        $result = \App\Models\Option::lists('value','option');
        return $result->toArray();
    }

}

function sendMail(array $request){
    $recipent = $request['recipent'];
    $users = \App\Models\User::whereIn('email',$recipent)->get();
    if(!isset($request['title']))
        $request['title'] = '';
    if(!isset($request['content']))
        $request['content'] = '';

    if(isset($request['template'])){
        $template = \App\Models\EmailTemplate::where('id',$request['template'])->first();
        $request['message'] = $template->template;
        $request['subject'] = $template->title;
    }

    foreach ($users as $to){
        $request['message'] = str_replace(['[username]','[name]','[email]','[active]','[token]','[password]','[n.title]','[n.content]'],[$to->username,$to->name,$to->email,url('/').'/user/active/'.$to->token,url('/').'/user/reset/token/'.$to->token,decrypt($to->password),$request['title'],$request['content']],$request['message']);
        Mail::send('email.content',['content'=>$request['message']],function ($mail) use ($to,$request){
            $mail->to($to->email,$to->name);
            $mail->subject($request['subject']);
            $mail->from(get_option('site_email','no-reply@site.com'),get_option('site_title'));
            if(isset($request['attach']) && $request['attach']!='') {
                $mail->attach(public_path() . $request['attach']);
            }
        });
    }

    if(count(Mail::failures())>0)
        return false;
    else
        return count($users);

}

function expandSidebarMenu($nav = null,$array = null){
    $segmentList = request()->segments();
    $segment = $segmentList[1];
    if($nav == $segment)
        echo 'nav-expanded nav-active';
    else {
        if($array != null && is_array($array) && in_array($nav,$array))
            return;
        else
            echo 'hidden';
    }
}

function activeSidebarSubmenu($nav = null,$subMenu = null){

    if($nav == null || $subMenu == null)
        return;

    $segmentList = request()->segments();
    $segment_nav = $segmentList[1];
    if(isset($segmentList[2]))
    $segment_submenu = $segmentList[2];

    if($nav == $segment_nav && $subMenu == $segment_submenu)
        echo 'class="nav-active active"';
    else
        return;
}

function dateToTimestamp($date){
    $jdate = new App\Classes\jDateTime;
    $array_expire = explode('/',$date);
    return $time = $jdate->mktime(0,0,0,$array_expire[2],$array_expire[1],$array_expire[0]);
}

function contentMenu(){
    $menus = \App\Models\ContentCategory::where('parent_id',0)->get();
    $menus =  $menus->toArray();
    foreach ($menus as $index=>$menu){
        $submenu = \App\Models\ContentCategory::where('parent_id',$menu['id'])->get();
        if($submenu)
            $menus[$index]['submenu'] = $submenu->toArray();
    }
    return $menus;
}

function selectMenu(){
    $menus = \App\Models\ContentCategory::where('parent_id',0)->get();
    $menus =  $menus->toArray();
    foreach ($menus as $index=>$menu){
        $submenu = \App\Models\ContentCategory::where('parent_id',$menu['id'])->get();
        if($submenu->count() == 0)
            $menus[$index]['submenu'] = [$menu];
        else
            $menus[$index]['submenu'] = $submenu->toArray();
    }
    return $menus;
}

function findKey($array, $key, $keySearch)
{
    foreach ($array as $item) {
        if ($item[$key] == $keySearch){
            return true;
        }
    }
    return false;
}

function num2str($money)
{
    return $money;
}

function checkboxValue($checkbox,$value,$defaultvalue){
    if(isset($checkbox) && $checkbox == $value)
        return $value;
    else
        return $defaultvalue;
}

function notify($msg,$type){
    \Illuminate\Support\Facades\Session::flash('msg',$msg);
    \Illuminate\Support\Facades\Session::flash('type',$type);
}

function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

function getRate($user){
    $result = [];
    $rates = \App\Models\UserRate::all();
    $_user = \App\Models\User::withCount(['contents'=>function($cc){
        $cc->where('mode','publish');
    },'sells','buys'])->with('point')->find($user['id']);

    $p = [];
    foreach ($_user->point as $point){
        $post_count = 0;
        $post_rate = 0;
        if($point->mode == 'post'){
            $post_count ++;
            $post_rate += $point->rate;
            $p['post_avg'] = $post_rate/$post_count;
        }
        $content_count = 0;
        $content_rate = 0;
        if($point->mode == 'content'){
            $content_count ++;
            $content_rate += $point->rate;
            $p['content_avg'] = $content_rate/$content_count;
        }
        $support_count = 0;
        $support_rate = 0;
        if($point->mode == 'support'){
            $support_count ++;
            $support_rate += $point->rate;
            $p['support_avg'] = $support_rate/$support_count;
        }
    }

    foreach ($rates as $r){
        $avg = explode(',',$r->value);
        if($r->mode == 'videocount' && $avg[0]<=$_user->contents_count && $_user->contents_count<=$avg[1]){
            $result []= $r->toArray();
        }
        if($r->mode == 'sellcount' && $avg[0]<=$_user->sells_count && $_user->sells_count<=$avg[1]){
            $result []= $r->toArray();;
        }
        if($r->mode == 'buycount' && $avg[0]<=$_user->buys_count && $_user->buys_count<=$avg[1]){
            $result []= $r->toArray();;
        }
        if($r->mode == 'day' && $avg[0]<=((time()-$_user->create_at)/86400) && ((time()-$_user->create_at)/86400)<=$avg[1]) {
            $result [] = $r->toArray();;
        }
        if(isset($p['content_avg']) && $r->mode == 'productrate' && $avg[0]<=$p['content_avg'] && $p['content_avg']<=$avg[1]) {
            $result [] = $r->toArray();;
        }
        if(isset($p['support_avg']) && $r->mode == 'supportrate' && $avg[0]<=$p['support_avg'] && $p['support_avg']<=$avg[1]) {
            $result [] = $r->toArray();;
        }
        if(isset($p['post_avg']) && $r->mode == 'postrate' && $avg[0]<=$p['post_avg'] && $p['post_avg']<=$avg[1]) {
            $result [] = $r->toArray();;
        }
    }

    return $result;
}

function getRateById($userId){
    $result = [];
    $rates = \App\Models\UserRate::all();
    $_user = \App\Models\User::withCount(['contents'=>function($cc){
        $cc->where('mode','publish');
    },'sells','buys'])->with('point')->find($userId);

    $p = [];
    foreach ($_user->point as $point){
        $post_count = 0;
        $post_rate = 0;
        if($point->mode == 'post'){
            $post_count ++;
            $post_rate += $point->rate;
            $p['post_avg'] = $post_rate/$post_count;
        }
        $content_count = 0;
        $content_rate = 0;
        if($point->mode == 'content'){
            $content_count ++;
            $content_rate += $point->rate;
            $p['content_avg'] = $content_rate/$content_count;
        }
        $support_count = 0;
        $support_rate = 0;
        if($point->mode == 'support'){
            $support_count ++;
            $support_rate += $point->rate;
            $p['support_avg'] = $support_rate/$support_count;
        }
    }

    foreach ($rates as $r){
        $avg = explode(',',$r->value);
        if($r->mode == 'videocount' && $avg[0]<$_user->contents_count && $_user->contents_count<$avg[1]){
            $nr = $r->toArray();
            $nr['title'] = trans('admin.from').$avg[0].trans('admin.to').$avg[1].trans('admin.courses');
            $result []= $nr;
        }
        if($r->mode == 'sellcount' && $avg[0]<$_user->sells_count && $_user->sells_count<$avg[1]){
            $nr = $r->toArray();
            $nr['title'] = trans('admin.from').$avg[0].trans('admin.to').$avg[1].trans('admin.sales');
            $result []= $nr;
        }
        if($r->mode == 'buycount' && $avg[0]<$_user->buys_count && $_user->buys_count<$avg[1]){
            $nr = $r->toArray();
            $nr['title'] = trans('admin.from').$avg[0].trans('admin.to').$avg[1].trans('admin.purchases');
            $result []= $nr;
        }
        if($r->mode == 'day' && $avg[0]<((time()-$_user->create_at)/86400) && ((time()-$_user->create_at)/86400)<$avg[1]) {
            $nr = $r->toArray();
            $nr['title'] = trans('admin.from').$avg[0].trans('admin.to').$avg[1].trans('admin.registration_days');
            $result []= $nr;
        }
        if(isset($p['content_avg']) && $r->mode == 'productrate' && $avg[0]<$p['content_avg'] && $p['content_avg']<$avg[1]) {
            $nr = $r->toArray();
            $nr['title'] = trans('admin.from').$avg[0].trans('admin.to').$avg[1].trans('admin.course_rate');
            $result []= $nr;
        }
        if(isset($p['support_avg']) && $r->mode == 'supportrate' && $avg[0]<$p['support_avg'] && $p['support_avg']<$avg[1]) {
            $nr = $r->toArray();
            $nr['title'] = trans('admin.from').$avg[0].trans('admin.to').$avg[1].trans('admin.support_rate');
            $result []= $nr;
        }
        if(isset($p['post_avg']) && $r->mode == 'postrate' && $avg[0]<$p['post_avg'] && $p['post_avg']<$avg[1]) {
            $nr = $r->toArray();
            $nr['title'] = trans('admin.from').$avg[0].trans('admin.to').$avg[1].trans('admin.physical_sales_rate');
            $result []= $nr;
        }
    }

    return $result;
}

function groupDay($array,$day = 1){
    $fTime = strtotime('-'.$day.' day');
    $lTime = $fTime + 86400;
    $result = 0;
    foreach ($array as $a){
        if($fTime <= $a->create_at && $a->create_at <= $lTime)
            $result++;
    }
    return $result;
}

function random_color() {
    return rand(0,255).','.rand(0,255).','.rand(0,255);
}

function timeToSecond($time){
    $time = $time.':00';
    $seconds = strtotime("1970-01-01 $time UTC");
    return $seconds;
}

function to_latin_num($string) {
    return $string;
}

function sendNotification($senderId, $source, $message_id, $recipentType, $recipentList){
    $message = \App\Models\NotificationTemplate::find($message_id);

    if(!isset($message))
        return false;

    ## Filters
    $title = str_replace(
        array_keys($source),
        array_values($source),
        $message->title);

    $message = str_replace(
        array_keys($source),
        array_values($source),
        $message->template);

    $notification = \App\Models\Notification::create([
        'user_id'=>$senderId,
        'title'=>$title,
        'msg'=>$message,
        'create_at'=>time(),
        'recipent_type'=>$recipentType,
        'recipent_list'=>$recipentList,
    ]);

    ## Send Email ##
    if($recipentType == 'user') {
        $userEmail = \App\Models\User::find($recipentList);
        sendMail([
            'recipent' => [$userEmail->email],
            'template' => get_option('email_notification_template', 0),
            'subject' => $title,
            'title' => $title,
            'content' => $message,
        ]);
    }
    ## End Email Section ##

    if($notification->id)
        return true;
    else
        return false;
}

function toTimestamp($date){
    return strtotime($date)+12600;
}

function price($content_id,$category_id,$p){
    $price['price'] = $p;
    $price['price_txt'] = $p.currencySign();
    $percent = 0;
    if($p == 0 || !isset($p) || $p == '') {
        $price['price_txt'] = 'free';
    }
    ## Single Content
    $contentDiscount = \App\Models\DiscountContent::where('type','content')->where('off_id',$content_id)->where('first_date','<',time())->where('last_date','>',time())->where('mode','publish')->orderBy('id','DESC')->first();
    if(isset($contentDiscount)){
        $percent = $contentDiscount->off;
        $price['price'] = $p - (($contentDiscount->off/100)*$p);
        $price['price_txt'] = $price['price'].currencySign();
    }
    ## Category Content
    $categoryDiscount = \App\Models\DiscountContent::where('type','category')->where('off_id',$category_id)->where('first_date','<',time())->where('last_date','>',time())->where('mode','publish')->orderBy('id','DESC')->first();
    if(isset($categoryDiscount) && is_array($categoryDiscount) && count($categoryDiscount)>0){
        $percent = $categoryDiscount->off;
        $price['price'] = $p - (($categoryDiscount->off/100)*$p);
        $price['price_txt'] = $price['price'].currencySign();
    }
    ## All Content
    $allDiscount = \App\Models\DiscountContent::where('type','all')->where('first_date','<',time())->where('last_date','>',time())->where('mode','publish')->orderBy('id','DESC')->first();
    if(isset($allDiscount) && count($allDiscount)>0){
        $percent = $allDiscount->off;
        $price['price'] = $p - (($allDiscount->off/100)*$p);
        $price['price_txt'] = $price['price'].currencySign();
    }
    ## User Group
    global $user;
    if($user){
        $userGroup = \App\Models\Usercategories::where('id',$user['category_id'])->first();
        if($userGroup){
            $percent += $userGroup->off;
            $price['price'] = $p - (($percent/100)*$p);
            $price['price_txt'] = $price['price'].currencySign();
        }
    }
    ## No Discount
    return $price;
}

function pricePay($content_id,$category_id,$p){
    $price['price'] = $p;
    $price['price_txt'] = $p.currencySign();
    $percent = 0;
    if($p == 0 || !isset($p) || $p == '') {
        $price['price_txt'] = 'free';
    }
    ## Single Content
    $contentDiscount = \App\Models\DiscountContent::where('type','content')->where('off_id',$content_id)->where('first_date','<',time())->where('last_date','>',time())->where('mode','publish')->orderBy('id','DESC')->first();
    if(isset($contentDiscount) && count($contentDiscount)>0){
        $percent = $contentDiscount->off;
        $price['price'] = $p - (($contentDiscount->off/100)*$p);
        $price['price_txt'] = $price['price'].currencySign();
    }
    ## Category Content
    $categoryDiscount = \App\Models\DiscountContent::where('type','category')->where('off_id',$category_id)->where('first_date','<',time())->where('last_date','>',time())->where('mode','publish')->orderBy('id','DESC')->first();
    if(isset($categoryDiscount) && count($categoryDiscount)>0){
        $percent = $categoryDiscount->off;
        $price['price'] = $p - (($categoryDiscount->off/100)*$p);
        $price['price_txt'] = $price['price'].currencySign();
    }
    ## All Content
    $allDiscount = \App\Models\DiscountContent::where('type','all')->where('first_date','<',time())->where('last_date','>',time())->where('mode','publish')->orderBy('id','DESC')->first();
    if(isset($allDiscount) && count($allDiscount)>0){
        $percent = $allDiscount->off;
        $price['price'] = $p - (($allDiscount->off/100)*$p);
        $price['price_txt'] = $price['price'].currencySign();
    }
    ## User Group
    global $user;
    if($user){
        $userGroup = \App\Models\Usercategories::where('id',$user['category_id'])->first();
        if($userGroup){
            $percent += $userGroup->off;
            $price['price'] = $p - (($percent/100)*$p);
            $price['price_txt'] = $price['price'].currencySign();
        }
    }
    ## Gift && Off
    if(session('gift') != ''){
        $gift = \App\Models\Discount::where('create_at','<',time())->where('expire_at','>',time())->find(session('gift'));
        if($gift->type == 'gift')
        {
            $price['price'] = $price['price'] - $gift->off;
            $price['price_txt'] = $price['price'].currencySign();
        }
        else
        {
            $price['price'] = $price['price'] - intval(($gift->off/100) * $price['price']);
            $price['price_txt'] = $price['price'].currencySign();
        }
        session()->forget('gift');
    }


    ## No Discount
    return $price;
}

function contentMeta($content_id,$meta_key,$default = ""){
    $value = \App\Models\ContentMeta::where('content_id',$content_id)->where('option',$meta_key)->take(1)->value('value');
    if($value == null)
        return $default;
    else
        return $value;
}

function userMeta($user_id,$meta_key,$default = ""){
    $value = \App\Models\Usermeta::where('user_id',$user_id)->where('option',$meta_key)->take(1)->value('value');
    if($value == null)
        return $default;
    else
        return $value;
}

function setUserMeta($user_id,$meta_key,$meta_value){
    \App\Models\Usermeta::updateOrCreate(
        ['option'=>$meta_key,'user_id'=>$user_id],
        ['value'=>$meta_value]
    );
}

function listByKey($array = null,$key = null){
    $list = [];
    foreach ($array as $index=>$arr){
            $id = $arr[$key];
            if(!array_key_exists($id,$list))
                $list[$id][] = $arr;
            else
                $list[$id]['child'][] = $arr;
    }
    foreach ($list as $index=>$li){
        if(count($li)==0)
            unset($list[$index]);
    }
    return $list;
}

function userAddress($userId){
    if(userMeta($userId,'address','') == ''){
        return '<b style="color: red">Your address not found!</b>';
    }
    return userMeta($userId,'state',trans('admin.not_defined')).' - '.userMeta($userId,'city',trans('admin.not_defined')).' - '.userMeta($userId,'address',trans('admin.not_defined')).' - Zip Code '.userMeta($userId,'postalcode',trans('admin.not_defined'));
}

function getNotification($user_id, $type, $notification_id = null){
    if($notification_id == null) {
        $notification = 0;
        if ($type == 'ticket') {
            $Tickets = \App\Models\Tickets::select('id')->where('user_id', $user_id)->get();
            $Messages = \App\Models\TicketsMsg::whereIn('ticket_id', $Tickets->toArray())->where('user_id', '<>', $user_id)->get();
            foreach ($Messages as $message) {
                $view = \App\Models\View::where('user_id', $user_id)->where('notification_id', $message->id)->where('type', 'ticket')->count();
                if ($view == 0)
                    $notification++;
            }
        }
        if ($type == 'notification') {
            $Notifications = \App\Models\Notification::where('recipent_list', $user_id)->get();
            foreach ($Notifications as $noti) {
                $view = \App\Models\View::where('user_id', $user_id)->where('notification_id', $noti->id)->where('type', 'notification')->count();
                if ($view == 0)
                    $notification++;
            }
        }
        if ($type == 'comment') {
            $content = \App\Models\Content::where('user_id', $user_id)->where('mode', 'publish')->pluck('id');
            $comments = \App\Models\ContentComment::whereIn('content_id', $content)->get();
            foreach ($comments as $comment) {
                $view = \App\Models\View::where('user_id', $user_id)->where('notification_id', $comment->id)->where('type', 'comment')->count();
                if ($view == 0)
                    $notification++;
            }
        }
        if ($type == 'sell_all') {
            $sell_all = \App\Models\Sell::where('user_id', $user_id)->get();
            foreach ($sell_all as $sell) {
                $view = \App\Models\View::where('user_id', $user_id)->where('notification_id', $sell->id)->where('type', 'sell')->count();
                if ($view == 0)
                    $notification++;
            }
        }
        if ($type == 'sell_post') {
            $sell_all = \App\Models\Sell::where('user_id', $user_id)->where('type', 'post')->get();
            foreach ($sell_all as $sell) {
                $view = \App\Models\View::where('user_id', $user_id)->where('notification_id', $sell->id)->where('type', 'sell')->count();
                if ($view == 0)
                    $notification++;
            }
        }
        if ($type == 'sell_download') {
            $sell_all = \App\Models\Sell::where('user_id', $user_id)->where('type', 'download')->get();
            foreach ($sell_all as $sell) {
                $view = \App\Models\View::where('user_id', $user_id)->where('notification_id', $sell->id)->where('type', 'sell')->count();
                if ($view == 0)
                    $notification++;
            }
        }
        return $notification;
    }else{
        $View = \App\Models\View::where('type',$type)->where('user_id',$user_id)->where('notification_id',$notification_id)->count();
        if($View == 0)
            return false;
        else
            return true;
    }
}

function setNotification($user_id, $type, $notification_id){
    $new = \App\Models\View::updateOrCreate([
       'user_id'=>$user_id,
       'type'=>$type,
       'notification_id'=>$notification_id
    ],[
       'user_id'=>$user_id,
       'type'=>$type,
       'notification_id'=>$notification_id
    ]);
    if($new)
        return 1;
    else
        return 0;
}

function sendSms($phone , $message){
    return true;
}

function checkAccess($key){
    global $Access;
    if($Access == 'all')
        return true;

    if(!isset($Access) || $Access == null)
        return true;
    if(in_array($key, $Access))
        return true;

    return false;
}

function checkSubscribeSell($product){
    if(is_numeric($product->price_3) && $product->price_3 > 0)
        return true;
    if(is_numeric($product->price_6) && $product->price_6 > 0)
        return true;
    if(is_numeric($product->price_9) && $product->price_9 > 0)
        return true;
    if(is_numeric($product->price_12) && $product->price_12 > 0)
        return true;

    return false;
}

function productSpendTime($product_id){
    $usages = \App\Models\Usage::where('product_id', $product_id)->count();
    return $usages * 5;
}

function productTopViewer($product_id){
    $Usages = \App\Models\Usage::where('product_id', $product_id)->select('user_id', DB::raw('count(*) as total'))->groupBy('user_id')->orderBy('total','DESC')->first();
    if($Usages){
        $user = \App\Models\User::find($Usages->user_id);
        if($user)
        return $user->username;
    }
    return false;
}

function stripTagsAll($arr = []){
    if(!is_array($arr))
        return '';

    $json = strip_tags(json_encode($arr));
    return json_decode($json, true);
}

function checkUrl($url = null){
    if($url == null)
        return null;

    if(strpos($url,'http') !== false){
        return $url;
    }

    return url('/').$url;
}

function getCategoryCount($id){
    $count = \App\Models\Content::where('mode','publish')->where('category_id', $id)->count();
    return $count;
}

