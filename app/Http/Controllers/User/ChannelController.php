<?php

namespace App\Http\Controllers\User;

use App\Models\ChannelRequest;
use App\Models\ChannelVideo;
use App\Models\Content;
use function Couchbase\basicDecoderV1;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;

class ChannelController extends Controller
{

    public function channelList()
    {
        global $user;
        $channels = Channel::withCount('contents')->where('user_id',$user['id'])->get();
        return view('user.channel.list',['channels'=>$channels]);
    }
    public function channelDelete($id){
        global $user;
        Channel::where('id',$id)->where('user_id',$user['id'])->delete();
        return back();
    }

    public function channelEdit($id)
    {
        global $user;
        $item = Channel::where('id',$id)->where('user_id',$user['id'])->first();
        $channels = Channel::where('user_id',$user['id'])->get();
        if($item)
            return view('user.channel.edit',['edit'=>$item,'channels'=>$channels]);
        else
            return back();
    }
    public function channelEditStore($id,Request $request)
    {
        global $user;
        $request->request->add(['mode'=>'pending']);
        Channel::find($id)->where('user_id',$user['id'])->update($request->all());
        $request->session()->flash('Message','successfull');
        return back();
    }

    public function channelNew(){
        return view('user.channel.new');
    }
    public function channelStore(Request $request)
    {
        global $user;
        $ifChannelExist = Channel::where('username',$request->username)->first();
        if(!empty($ifChannelExist)){
            $request->request->add(['mode'=>'pending']);
            $request->session()->flash('Message','duplicate_username');
            return back();
        }else {
            $request->request->add(['user_id'=>$user['id'],'mode'=>get_option('user_channel_mode')]);
            Channel::insert($request->all());
            $request->session()->flash('Message','successfull');
            return back();
        }
    }

    public function channelRequest($id){
        global $user;
        $channelsRequest = ChannelRequest::with('channel')->where('user_id',$user['id'])->orderBy('id','DESC')->get();
        $channels = Channel::withCount('contents')->where('user_id',$user['id'])->get();
        return view('user.channel.request',['id'=>$id,'requests'=>$channelsRequest,'channels'=>$channels]);
    }
    public function channelRequestStore(Request $request){
        global $user;
        $check = Channel::where('user_id',$user['id'])->find($request->channel_id);
        if(!$check)
            return back();

        ChannelRequest::create([
            'title'=>$request->title,
            'channel_id'=>$request->channel_id,
            'user_id'=>$user['id'],
            'mode'=>'draft',
            'attach'=>$request->attach,
            'create_at'=>time()
        ]);
        return redirect()->back()->with('msg',trans('main.channel_success'));
    }

    public function chanelVideo($id){
        global $user;
        $chanel = Channel::with('contents.content')->where('user_id',$user['id'])->find($id);
        if(!$chanel)
            return abort(404);

        $userContents = Content::where('mode','publish')->where('user_id',$user['id'])->get();
        return view('user.channel.video',['chanel'=>$chanel,'userContents'=>$userContents]);
    }
    public function chanelVideoStore(Request $request,$id){
        global $user;
        $chanel = Channel::where('user_id',$user['id'])->find($id);
        if(!$chanel)
            return abort(404);

        ChannelVideo::create([
            'content_id'=>$request->content_id,
            'user_id'=>$user['id'],
            'chanel_id'=>$chanel->id,
            'create_at'=>time()
        ]);

        return redirect()->back()->with('msg',trans('main.add_success'));
    }
    public function chanelVideoDelete($id){
        global $user;
        ChannelVideo::where('user_id',$user['id'])->find($id)->delete();
        return redirect()->back();
    }
}
