<?php

namespace App\Http\Controllers\User;

use App\Models\ContentComment;
use App\Models\ContentSupport;
use App\Models\Notification;
use App\Models\TicketsMsg;
use App\Models\TicketsUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tickets;
use App\Models\TicketsCategory;
use App\Models\Content;
use Illuminate\Support\Facades\Input;

class TicketController extends Controller
{
    public function lists(){
        global $user;
        $ticket_invite = TicketsUser::where('user_id',$user['id'])->pluck('ticket_id');
        $tickets = Tickets::with('category','messages')->orderBy('id','DESC')->where('user_id',$user['id'])->orWhereIn('id',$ticket_invite->toArray())->get();
        $category = TicketsCategory::get();
        return view('user.ticket.list',['lists'=>$tickets,'category'=>$category]);
    }
    public function store(Request $request){

        global $user;

        $newTicketArray = [
            'title'=>$request->title,
            'user_id'=>$user['id'],
            'create_at'=>time(),
            'mode'=>'open',
            'category_id'=>$request->category_id,
            'attach'=>$request->attach
        ];

        $newTicket = Tickets::insertGetId($newTicketArray);

        $newMsgArray = [
            'ticket_id'=>$newTicket,
            'msg'=>$request->msg,
            'create_at'=>time(),
            'user_id'=>$user['id'],
            'mode'=>'user',
            'attach'=>$request->attach
        ];

        $newMsg = TicketsMsg::insert($newMsgArray);

        ## Notification Center
        sendNotification(0,['[t.title]'=>$request->title],get_option('notification_template_ticket_new'),'user',$user['id']);

        return back();

    }
    public function reply($id){
        global $user;
        $wherein = TicketsUser::where('user_id',$user['id'])->where('ticket_id',$id)->pluck('ticket_id');
        $ticket = Tickets::with(['messages'=>function($q){
            $q->orderBy('id','DESC');
        }])->where(function ($w) use($user,$wherein){
            $w->where('user_id',$user['id'])->orwhereIn('id',$wherein->toArray());
        })->where('id',$id)->first();

        ## Update Notification
        foreach ($ticket->messages as $msgUpdate){
            TicketsMsg::where('mode','<>','user')->where('id',$msgUpdate->id)->update(['view'=>1]);
        }
        return view('user.ticket.reply',['ticket'=>$ticket]);
    }
    public function replyStore(Request $request){
        global $user;
        $ticket = Tickets::find($request->ticket_id);
        $ticket_user = TicketsUser::where('ticket_id',$request->ticket_id)->where('user_id',$user['id'])->first();
        if($ticket->user_id == $user['id'] || $ticket_user){
            $insertArray = [
                'create_at'=>time(),
                'ticket_id'=>$request->ticket_id,
                'attach'=>$request->attach,
                'user_id'=>$user['id'],
                'mode'=>'user',
                'msg'=>$request->msg
            ];
            TicketsMsg::insert($insertArray);
            if($ticket->mode == 'close'){
                $ticket->update(['mode'=>'open']);
            }
        }
        return back();
    }
    public function close($id){
        global $user;
        $ticket = Tickets::where('user_id',$user['id'])->find($id);
        $ticket->update(['mode'=>'close']);
        return back();
    }

    ## Comment Section
    public function comments(){
        global $user;
        $userContent = Content::where('user_id',$user['id'])->where('mode','publish')->pluck('id')->toArray();
        $comments = ContentComment::with(['user','content'])->whereIn('content_id',$userContent)->Where('mode','publish')->orderBy('id','DESC');
        $count = $comments->count();
        if(Input::get('p')!=null)
            $comments->skip(Input::get('p')*20);

        $comments->take(20);
        return view('user.ticket.commentList',['lists'=>$comments->get(),'count'=>$count]);
    }

    ## Notifications
    public function notifications(){
        global $user;
        $notifications = Notification::where('recipent_type','user')
            ->where('recipent_list',$user['id'])
            ->orWhere('recipent_type','all')
            ->where('mode','publish')
            ->orderBy('id','DESC');

        $count = $notifications->count();
        if(Input::get('p')!=null)
            $notifications->skip(Input::get('p')*20);

        $notifications->take(20);

        return view('user.ticket.notificationList',['lists'=>$notifications->get(),'count'=>$count]);
    }

    ## Support
    public function support(){
        global $user;
        $support = Content::with(['supports'=>function($q){
            $q->with(['sender'])->where('mode','publish');
        }])->where('user_id',$user['id'])->where('mode','publish')->get();
        return view('user.ticket.supportList',['supports'=>$support]);
    }
    public function supportJson($content_id,$sender_id){
        global $user;
        if(!$user)
            return abort(404);

        $supports = ContentSupport::with(['sender'=>function($q){
            $q->select('id','name','username');
        }])
            ->where('content_id',$content_id)
            ->where('sender_id',$sender_id)
            ->get();

        foreach ($supports as $index=>$sup){
            if($sup->user_id != $sup->supporter_id && $sup->mode != 'publish')
                $supports->forget($index);
        }
        return $supports;
    }
    public function supportStore(Request $request){
        global $user;
        if(!$user)
            return abort(404);

        $content = Content::where('mode','publish')->where('user_id',$user['id'])->find($request->content_id);
        if(!$content)
            return abort(404);

        $support = ContentSupport::create([
           'comment'=>$request->comment,
            'user_id'=>$user['id'],
            'supporter_id'=>$user['id'],
            'sender_id'=>$request->sender_id,
            'create_at'=>time(),
            'name'=>$user['name'],
            'content_id'=>$request->content_id,
            'rate'=>'0',
            'mode'=>'draft'
        ]);

        if($support->id)
            return $support;
    }
}
