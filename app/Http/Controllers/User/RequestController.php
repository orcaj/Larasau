<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Content;

class RequestController extends Controller
{
    public function lists(){
        global $user;
        $lists = Requests::where('user_id',$user['id'])->orWhere('requester_id',$user['id'])->with(['category','requester','suggestions'=>function($q){
            $q->with(['content','user']);
        }])->withCount(['fans','suggestions'])->orderBy('id','DESC')->get();
        $userContent = Content::where('user_id',$user['id'])->where('mode','publish')->get();
        return view('user.request.request',['lists'=>$lists,'menus'=>contentMenu(),'userContent'=>$userContent]);
    }
    public function admit(Request $request){
        global $user;
        $uRequest = Requests::where('requester_id',$user['id'])->find($request->request_id);
        if($uRequest){
            $uRequest->update(['content_id'=>$request->content_id]);
        }

        return redirect()->back();
    }
    public function store(Request $request){
        global $user;
        Requests::create([
            'user_id'=>0,
            'requester_id'=>$user['id'],
            'title'=>$request->title,
            'description'=>$request->description,
            'category_id'=>$request->category_id,
            'mode'=>'draft',
            'create_at'=>time()
        ]);
        return redirect()->back()->with('msg',trans('main.request_sent'));
    }
    public function editStore($id,Request $request){
        global $user;
        $req = Requests::where('requester_id',$user['id'])->find($id);
        if(!$req)
            return abort(404);
        $req->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'category_id'=>$request->category_id,
            'mode'=>'draft',
        ]);
        return redirect()->back()->with('msg',trans('main.request_edit'));
    }
    public function edit($id){
        global $user;
        $lists = Requests::where('user_id',$user['id'])->orWhere('requester_id',$user['id'])->with(['category','requester','suggestions'=>function($q){
            $q->with(['content','user']);
        }])->withCount(['fans','suggestions'])->orderBy('id','DESC')->get();
        $userContent = Content::where('user_id',$user['id'])->where('mode','publish')->get();
        $request = Requests::where('requester_id',$user['id'])->find($id);
        if(!$request)
            abort(404);
        return view('user.request.request',['lists'=>$lists,'menus'=>contentMenu(),'userContent'=>$userContent,'request'=>$request]);
    }
}
