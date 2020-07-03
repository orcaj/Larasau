<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{

    public function sendMail(Request $request)
    {

        $send = sendMail($request->toArray());
        if($send)
            $request->session()->flash('status',$send);
        else
            $request->session()->flash('status','error');
        return back();
    }

    public function templateLists(){
        $lists = EmailTemplate::all();
        return view('admin.email.templates',array('lists'=>$lists));
    }

    public function templateNew(){
        return view('admin.email.templateitem');
    }

    public function templateDelete($id){
        EmailTemplate::find($id)->delete();
        return back();
    }

    public function templateItem($id){
        $item = EmailTemplate::find($id);
        return view('admin.email.templateitem',array('item'=>$item));
    }

    public function templateEdit(Request $request){
        if($request->id == '') {
            $template = new EmailTemplate;
            $template->title = $request->title;
            $template->template = $request->template;
            $template->save();
            return redirect('/admin/email/template/item/'.$template->id);
        }else{
            $Template = EmailTemplate::find($request->id);
            $Template->update([
                'title'     => $request->title,
                'template'  => $request->template
            ]);
            $Template->refresh();
        }
        return back();
    }

    public function emailNew(){
        $userList = User::all();
        $template = EmailTemplate::all();
        return view('admin.email.new',array('users'=>$userList,'template'=>$template));
    }

}
