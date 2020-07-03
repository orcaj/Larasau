<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Models\NotificationTemplate;
use App\Models\Option;
use App\Models\Social;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class SettingController extends Controller
{
    public function store(Request $request,$lunch = null){
        if(isset($request->main_page_slider_content))
            $request->offsetSet('main_page_slider_content',implode(',',$request->main_page_slider_content));

        if(isset($request->site_language))
            $request->request->add(['site_language'=>strtolower($request->site_language)]);

        Option::updateOrNew($request->all());
        if($lunch != null)
            return Redirect::to(URL::previous().'#'.$lunch);
        else
            return back();
    }
    public function blog()
    {
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.blog',['_setting'=>$_setting]);
    }
    public function notification()
    {
        $notification_template = NotificationTemplate::all();
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.notification',['_setting'=>$_setting,'template'=>$notification_template]);
    }
    public function content()
    {
        $contents = Content::where('mode','publish')->get();
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.content',['_setting'=>$_setting,'contents'=>$contents]);
    }
    public function main()
    {
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.main',['_setting'=>$_setting]);
    }
    public function display()
    {
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.display',['_setting'=>$_setting]);
    }
    public function user()
    {
        $_setting = arrayToList(Option::all(),'option','value');
        $template = EmailTemplate::all();
        return view('admin.setting.user',['_setting'=>$_setting,'template'=>$template]);
    }
    public function profile(){
        global $admin;
        $admin = User::find($admin['id']);
        return view('admin.manager.profile',['user'=>$admin]);
    }
    public function profileMainUpdate(Request $request){
        global $admin;
        User::find($admin['id'])->update($request->all());
        return back();
    }
    public function profileSecurityUpdate(Request $request){
        global $admin;
        if($request->password != $request->re_password)
            return Redirect::back()->withErrors([trans('admin.pass_confirm_alert')]);
        else{
            User::find($admin['id'])->update(['password'=>encrypt($request->password)]);
            return Redirect::back()->withErrors([trans('admin.password_changed')]);
        }
    }
    public function social(){
        $list = Social::orderBy('sort')->get();
        return view('admin.setting.social',['lists'=>$list]);
    }
    public function socialStore(Request $request){
        if(isset($request->id)){

            Social::find($request->id)->update($request->all());
            return back();
        }else{
            Social::create($request->all());
            return back();
        }
    }
    public function socialEdit($id){
        $item = Social::find($id);
        $list = Social::orderBy('sort')->get();
        return view('admin.setting.social',['lists'=>$list,'item'=>$item]);
    }
    public function socialDelete($id){
        Social::find($id)->delete();
        return back();
    }
    public function footer(){
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.footer',['_setting'=>$_setting]);
    }
    public function term(){
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.term',['_setting'=>$_setting]);
    }
    public function pages(){
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.pages',['_setting'=>$_setting]);
    }
    public function defaults(){
        $_setting = arrayToList(Option::all(),'option','value');
        return view('admin.setting.default',['_setting'=>$_setting]);
    }
    public function about(){
        return view('admin.about');
    }
}
