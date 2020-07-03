<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Login;
use App\Models\Sell;
use App\Models\Usermeta;
use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {

        app()->setLocale(get_option('site_language','en'));
        if( ! $request->session()->has('user') && !Cookie::has('user')){
            return redirect('/user/login');
        }

        if(! $request->session()->has('user') && Cookie::has('user')){
            $cUser = decrypt(Cookie::get('user'));
            $admin = User::where('username',$cUser)->first();
            $login = Login::where('user_id', $admin->id)->orderBy('id','DESC')->first();
            if(get_option('duplicate_login',0) == 1) {
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
            Event::create([
                'user_id'   => $admin->id,
                'type'      => 'Cookie'
            ]);
            $request->session()->put('user',serialize($admin->toArray()));
        }

        session_start();
        global $user;
        $sessionUser = unserialize($request->session()->get('user'));
        if(!isset($sessionUser['id']))
            return redirect('/user/login');
        $user = User::with('category')->where('id',$sessionUser['id'])->first()->toArray();
        $usermeta = Usermeta::where('user_id',$user['id'])->get();
        $userMeta = arrayToList($usermeta,'option','value');
        $_SESSION["kc_disable"] = false;
        $_SESSION["kc_uploadedir"] = $user['username'];
        $_SESSION["kc_allow"] = true;

        view()->share('user',$user);
        view()->share('userMeta',$userMeta);

        return $next($request);

    }
}
