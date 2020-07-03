<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tickets;
use App\Models\TicketsCategory;
use App\Models\TicketsMsg;
use App\Models\TicketsUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class TicketController extends Controller
{
    public function tickets(){
        $tickets = Tickets::with('user','category')->orderBy('id','DESC')->paginate(10);
        return view('admin.ticket.list',['lists'=>$tickets]);
    }
    public function ticketsOpen(){
        $fdate = strtotime(Input::get('fsdate'));
        $ldate = strtotime(Input::get('lsdate'));

        $lists = Tickets::with('user','category')->where('mode','open');

        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('user')!==null)
            $lists->where('user_id',Input::get('user'));

        $lists = $lists->get();
        $users = User::all();
        return view('admin.ticket.listopen',['lists'=>$lists,'users'=>$users]);
    }
    public function ticketsClose(){
        $fdate = strtotime(Input::get('fsdate'));
        $ldate = strtotime(Input::get('lsdate'));

        $lists = Tickets::with(['user','category','users'=>function($q){
            $q->with('user');
        }])->where('mode','')->orWhere('mode','close');

        if($fdate>12601)
            $lists->where('create_at','>',$fdate);
        if($ldate>12601)
            $lists->where('create_at','<',$ldate);
        if(Input::get('user')!==null)
            $lists->where('user_id',Input::get('user'));

        $lists = $lists->get();
        $users = User::all();
        return view('admin.ticket.listclose',['lists'=>$lists,'users'=>$users]);
    }
    public function ticketDelete($id)
    {
        Tickets::find($id)->delete();
        return back();
    }
    public function ticketClose($id)
    {
        Tickets::find($id)->update(['mode'=>'close']);
        return back();
    }
    public function ticketOpen($id)
    {
        Tickets::find($id)->update(['mode'=>'open']);
        return back();
    }
    public function ticketNew(){
        $users = User::all();
        $category = TicketsCategory::all();
        return view('admin.ticket.new',['users'=>$users,'category'=>$category]);
    }
    public function ticketNewStore(Request $request){
        global $admin;
        $ticket = Tickets::create([
            'user_id'=>$request->user_id,
            'title'=>$request->title,
            'create_at'=>time(),
            'update_at'=>time(),
            'mode'=>'open',
            'category_id'=>$request->category_id
        ]);
        TicketsMsg::create([
            'ticket_id'=>$ticket->id,
            'msg'=>$request->msg,
            'create_at'=>time(),
            'user_id'=>$admin['id'],
            'mode'=>'admin'
        ]);
        return redirect('admin/ticket/reply/'.$ticket->id);
    }


    public function ticketReply($id)
    {
        $ticket = Tickets::with(['users'=>function($q){
            $q->with('user');
        }])->find($id);
        $ticketMsg = TicketsMsg::where('ticket_id',$id)->with('user')->get();

        return view('admin.ticket.reply',['ticket'=>$ticket,'ticketMsg'=>$ticketMsg]);
    }
    public function ticketReplyEdit($tid,$id)
{
    $ticket = Tickets::find($tid);
    $ticketMsg = TicketsMsg::where('ticket_id',$tid)->with('user')->get();
    $item = TicketsMsg::find($id);
    return view('admin.ticket.reply',['ticket'=>$ticket,'ticketMsg'=>$ticketMsg,'item'=>$item]);
}
    public function ticketReplyDelete($id)
    {
        $ticket = TicketsMsg::find($id)->delete();
        return back();
    }
    public function ticketStore(Request $request,$id)
    {
        global $admin;
        $ticket = Tickets::find($id);
        if(!empty($request->id)){
            $edit = TicketsMsg::find($request->id);
            $edit->msg = $request->msg;
            $edit->attach = $request->attach;
            $edit->save();
        }else{
            $edit = new TicketsMsg;
            $edit->msg = $request->msg;
            $edit->ticket_id = $id;
            $edit->create_at = time();
            $edit->user_id = $admin['id'];
            $edit->attach = $request->attach;
            $edit->mode = 'admin';
            $edit->save();
        }
        $ticket->update(['update_at'=>time()]);
        ## Notification Center
        sendNotification(0,['[t.title]'=>$ticket->title],get_option('notification_template_ticket_reply'),'user',$ticket->user_id);

        return back();
    }


    public function category()
    {
        $list = TicketsCategory::withCount('tickets')->get();
        return view('admin.ticket.categroy',array('lists'=>$list));
    }
    public function categoryEdit($id)
    {
        $list = TicketsCategory::withCount('tickets')->get();
        $item = TicketsCategory::find($id);
        return view('admin.ticket.categroyedit',array('lists'=>$list,'item'=>$item));
    }
    public function categoryStore(Request $request)
    {

        if($request->edit != '') {
            $category = TicketsCategory::find($request->edit);
            $category->title = $request->title;
            $category->save();
        }
        else {
            $category = new TicketsCategory;
            $category->title = $request->title;
            $category->save();
        }
        return back();
    }
    public function categoryDelete($id)
    {
        TicketsCategory::find($id)->delete();
        return back();
    }



    public function ticketUser($id){
        $ticket = Tickets::find($id);
        $userss = User::all();
        $users = TicketsUser::with('user')->where('ticket_id',$id)->get();
        return view('admin.ticket.user',['users'=>$users,'ticket'=>$ticket,'userss'=>$userss]);
    }
    public function ticketUserStore(Request $request){
        TicketsUser::create($request->all());
        return back();
    }
    public function ticketUserDelete($id){
        TicketsUser::find($id)->delete();
        return back();
    }
}
