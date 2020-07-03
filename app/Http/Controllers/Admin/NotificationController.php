<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Models\Usercategories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class NotificationController extends Controller
{

    public function lists()
    {
        global $admin;
        $lists = Notification::with(['user','status'=>function($q) use($admin){
            $q->where('user_id',$admin['id']);
        }])->orderBy('id','DESC')->get();
        return view('admin.notification.list',['lists'=>$lists]);
    }
    public function notificationNew()
    {
        $users = User::all();
        $userCategory = Usercategories::all();
        return view('admin.notification.new',['users'=>$users,'userCategory'=>$userCategory]);
    }
    public function store(Request $request)
    {
        global $admin;
        if(!empty($request->id)) {
            $notification = Notification::find($request->id);
            $notification->update_at = time();
        }else {
            $notification = new Notification;
            $notification->create_at = time();
        }
            $notification->user_id = $admin['id'];
            $notification->title = $request->title;
            $notification->msg = $request->msg;
            $notification->recipent_type = $request->recipent_type;
            switch ($request->recipent_type){
                case 'user':
                    $notification->recipent_list = $request->recipent_list_user;
                    break;
                case 'users':
                    $notification->recipent_list = serialize($request->recipent_list_users);
                    break;
                case 'category':
                    $notification->recipent_list = $request->recipent_list_category;
                    break;
                default:
                    $notification->recipent_list = '';
            }
            if($notification->save())
                $request->session()->flash('status','success');
            else
                $request->session()->flash('status','error');
            return redirect('/admin/notification/edit/'.$notification->id);
        }
    public function notificationEdit($id)
    {
        $users = User::all();
        $userCategory = Usercategories::all();
        $item = Notification::find($id);
        return view('admin.notification.edit',['users'=>$users,'userCategory'=>$userCategory,'item'=>$item]);
    }
    public function notificationDelete($id){
        Notification::find($id)->delete();
        return back();
    }

    ## Template Section

    public function templateLists(){
        $lists = NotificationTemplate::orderBy('id','DESC')->get();
        return view('admin.notification.templates',array('lists'=>$lists));
    }
    public function templateNew(){
        return view('admin.notification.templateitem');
    }
    public function templateDelete($id){
        NotificationTemplate::find($id)->delete();
        return back();
    }
    public function templateItem($id){
        $item = NotificationTemplate::find($id);
        return view('admin.notification.templateitem',array('item'=>$item));
    }
    public function templateEdit(Request $request){
        if($request->id == '') {
            $template = new NotificationTemplate;
            $template->title = $request->title;
            $template->template = $request->template;
            $template->save();
            return redirect('/admin/notification/template/item/'.$template->id);
        }else{
            $template = NotificationTemplate::find($request->id);
            $template->title = $request->title;
            $template->template = $request->template;
            $template->save();
        }
        return back();
    }
}
