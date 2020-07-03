<?php

namespace App\Http\Middleware;

use App\Models\AdsBox;
use App\Models\ChannelRequest;
use App\Models\Content;
use App\Models\ContentComment;
use App\Models\Notification;
use App\Models\Sell;
use App\Models\Social;
use App\Models\Tickets;
use Closure;
use App\Models\User;
use App\Models\Usermeta;
use Illuminate\Support\Facades\Cookie;

class NotificationMiddleware
{
    public function handle($request, Closure $next)
    {
        app()->setLocale(get_option('site_language','en'));
        $sessionUser = unserialize($request->session()->get('user'));

        if(empty($sessionUser) && Cookie::get('user') !== null){
            $user = User::with('category')->where('username',decrypt(Cookie::get('user')))->first()->toArray();
            $usermeta = Usermeta::where('user_id',$user['id'])->get();
            $userMeta = arrayToList($usermeta,'option','value');
            view()->share('user',$user);
            view()->share('userMeta',$userMeta);
        }

        #### Set User In View Section ####
        ##################################
        global $user;
        if(empty($user) && !empty($sessionUser) && !empty($sessionUser['id'])){
            $user = User::with('category')->where('id',$sessionUser['id'])->first()->toArray();
            $usermeta = Usermeta::where('user_id',$user['id'])->get();
            $userMeta = arrayToList($usermeta,'option','value');
            view()->share('user',$user);
            view()->share('userMeta',$userMeta);
        }
        if(empty($user) && !empty($sessionUser) && !empty($sessionUser['id'])){
            $user = User::with('category')->where('id',$sessionUser['id'])->first()->toArray();
            view()->share('user',$user);
        }

        #### GET NOTIFICATIONS ####
        ###########################
        global $alert;

        if($sessionUser && !empty($sessionUser['id'])){
            $alert['notification'] = 0;
            if($sessionUser['last_view'] == null)
                $sessionUser['last_view'] = 0;

            $notification = Notification::with(['status'=>function($query) use ($sessionUser){ $query->where('user_id',$sessionUser['id']); }])->get();
            foreach ($notification as $noti){
                if(empty($noti->status->id))
                    $alert['notification']++;
            }

            $ticket = Tickets::with('messages')->whereHas('messages',function($query){
                $query->where('view','0')->where('mode','<>','user');
            })->where('user_id',$sessionUser['id'])->where('mode','open')->get();
            $alert['ticket'] = count($ticket);

            $content = Content::where('user_id',$user['id'])->where('mode','publish')->pluck('id');
            $alert['comment'] = ContentComment::whereIn('content_id',$content)->where('create_at','>',$sessionUser['last_view'])->count();

            $sell_all = Sell::where('user_id',$sessionUser['id'])->count();
            $alert['sell_all'] = $sell_all;

            $Sell_download = Sell::where('user_id',$sessionUser['id'])->where('view',0)->where('type','download')->get();
            $alert['sell_download'] = count($Sell_download);

            $Sell_post = Sell::where('user_id',$sessionUser['id'])->where('view',0)->where('type','post')->get();
            $alert['sell_post'] = count($Sell_post);

            $alert['channel_request'] = ChannelRequest::where('mode','draft')->count();

        }

        $alert['all'] = $alert['ticket'] + $alert['comment'] + $alert['sell_download'] + $alert['sell_post'] + $alert['channel_request'];

        view()->share('alert',$alert);

        #### Get Footer Socials ####
        ############################
        $socials = Social::orderBy('sort')->get();
        view()->share('socials',$socials);

        #### Get Site Ads ####
        ######################
        $ads = AdsBox::where('mode','publish')->orderBy('sort')->get();
        view()->share('ads',$ads);

        return $next($request);
    }
}
