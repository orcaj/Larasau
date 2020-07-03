<?php

namespace App\Http\Controllers\Admin;

use App\Models\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use Illuminate\Support\Facades\Input;
use App\Models\User;
use App\Models\ContentCategory;

class RequestController extends Controller
{
    public function lists(){
        $fdate = strtotime(Input::get('fsdate'));
        $ldate = strtotime(Input::get('lsdate'));

        $category = ContentCategory::all();

        $lists = Requests::with('user','category')->withCount('fans')->orderBy('id','DESC');

        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('cat')!==null)
            $lists->where('category_id',Input::get('cat'));

        $lists = $lists->get();

        return view('admin.request.list',['lists'=>$lists,'category'=>$category]);
    }
    public function delete($id)
    {
        Requests::find($id)->delete();
        return back();
    }
    public function draft($id){
        $request = Requests::find($id);

        ## Notification Center
        sendNotification(0,['[r.title]'=>$request->title],get_option('notification_template_request_draft'),'user',$request->requester_id);

        $request->update(['mode'=>'draft']);
        return back();
    }
    public function publish($id){
        $request = Requests::with('requester')->find($id);

        ## Notification Center
        sendNotification(0,['[r.title]'=>$request->title],get_option('notification_template_request_publish'),'user',$request->requester_id);

        if($request->user_id != '' || $request->user_id != 0)
            sendNotification(0,['[r.title]'=>$request->title,'[u.name]'=>$request->requester->name],get_option('notification_template_request_req'),'user',$request->user_id);

        $request->update(['mode'=>'publish']);
        return back();
    }

    ## RECORD SECTION
    public function recordList(){
        $fdate = strtotime(Input::get('fsdate'));
        $ldate = strtotime(Input::get('lsdate'));

        $category = ContentCategory::all();

        $lists = Record::with('user','category')->withCount('fans')->orderBy('id','DESC');

        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('cat')!==null)
            $lists->where('category_id',Input::get('cat'));

        $lists = $lists->get();

        return view('admin.request.recordlist',['lists'=>$lists,'category'=>$category]);
    }
    public function recorddelete($id)
    {
        Record::find($id)->delete();
        return back();
    }
    public function recorddraft($id){
        Record::find($id)->update(['mode'=>'draft']);
        return back();
    }
    public function recordpublish($id){
        Record::find($id)->update(['mode'=>'publish']);
        return back();
    }
}
