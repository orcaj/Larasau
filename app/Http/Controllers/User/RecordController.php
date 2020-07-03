<?php

namespace App\Http\Controllers\User;

use App\Models\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ContentCategory;

class RecordController extends Controller
{
    public function lists(){
        global $user;
        $lists = Record::where('user_id',$user['id'])->with('category')->withCount('fans')->orderBy('id','DESC')->get();
        $userContent = Content::where('user_id',$user['id'])->where('mode','publish')->get();
        $contentMenu = ContentCategory::with(['childs','filters'=>function($q){
            $q->with(['tags']);
        }])->get();
        return view('user.record.record',['lists'=>$lists,'menus'=>$contentMenu,'userContent'=>$userContent]);
    }
    public function store(Request $request){
        global $user;
        Record::create([
            'user_id'=>$user['id'],
            'category_id'=>$request->category_id,
            'content_id'=>$request->content_id,
            'title'=>$request->title,
            'image'=>$request->image,
            'description'=>$request->description,
            'mode'=>'draft',
            'create_at'=>time()
        ]);
        return redirect()->back()->with('msg',trans('main.content_approval'));
    }
    public function edit($id){
        global $user;
        $lists = Record::where('user_id',$user['id'])->with('category')->withCount('fans')->orderBy('id','DESC')->get();
        $userContent = Content::where('user_id',$user['id'])->where('mode','publish')->get();
        $record = Record::where('user_id',$user['id'])->find($id);
        $contentMenu = ContentCategory::with(['childs','filters'=>function($q){
            $q->with(['tags']);
        }])->get();
        if(!$record)
            abort(404);
        return view('user.record.record',['lists'=>$lists,'menus'=>$contentMenu,'userContent'=>$userContent,'record'=>$record]);
    }
    public function delete($id){
        global $user;
        $record = Record::where('user_id',$user['id'])->find($id);
        $record->update(['mode'=>'delete']);
        return redirect()->back()->with('msg',trans('main.unpublish_request_sent'));
    }
    public function editStore($id,Request $request){
        global $user;
        $record = Record::where('user_id',$user['id'])->find($id);
        $record->update([
            'user_id'=>$user['id'],
            'category_id'=>$request->category_id,
            'content_id'=>$request->content_id,
            'title'=>$request->title,
            'image'=>$request->image,
            'description'=>$request->description,
            'mode'=>'draft',
        ]);
        return redirect()->back()->with('msg',trans('main.content_edit'));
    }
}
