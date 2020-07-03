@extends('admin.newlayout.layout',['breadcom'=>['Support','Reply Ticket',$ticket->title]])
@section('title')
    {{{ trans('admin.reply_ticket') }}}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <a href="/admin/ticket/user/{{{ $ticket->id or 0 }}}" class="btn btn-primary">{{{ trans('admin.add_user_conversation') }}}</a>
            &nbsp;&nbsp;
            <span>{{{ trans('admin.ticket_created_by') }}} </span>
            <span><a href="/profile/{{{ $ticket->user->id or '' }}}" target="_blank">{{{ $ticket->user->username or '' }}}</a></span>
            <span> {{{ trans('admin.and_this_users_invited') }}} </span>
            <span>
                @foreach($ticket->users as $tUser)
                    &nbsp;<a href="/profile/{{{ $tUser->user->id or 0 }}}" target="_blank">{{{ $tUser->user->username or '' }}}</a>&nbsp;
                @endforeach
            </span>
        </div>
    </div>

    @foreach($ticketMsg as $msg)
        <section class="card">
        @if($msg->mode == 'user')
            <header class="card-header">
        @else
            <header class="card-header" style="background: #008DEF">
        @endif
            <div class="card-header-action" style="float: right;position: relative;right: 10px;">
                @if($msg->attach != null && $msg->attach != '')
                    <a href="{!! $msg->attach or '' !!}" target="_blank" class="panel-action custom-reply"><i class="fa fa-paperclip" style="color: gray"></i></a>
                @endif
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

            @if($msg->mode == 'user')
                <h4 class="card-title">{{{ trans('admin.user') }}} - {{{ $msg->user->name or '' }}}</h4>
            @else
                <h4 class="card-title" style="color: #fafafa"> {{{ trans('admin.support_staff') }}} - {{{ $msg->user->name or '' }}}</h4>
            @endif
        </header>
        <div class="card-body">
            {!! $msg->msg !!}
            <hr>
                <a href="/admin/ticket/reply/{{{ $msg->ticket_id }}}/edit/{{{ $msg->id }}}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                <a href="#" title="Delete" data-href="/admin/ticket/reply/delete/{{{ $msg->id }}}" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                <span class="float-right custom-reply-2">{{{ date('d F Y : H:i',$msg->create_at) }}}</span>
        </div>
        </section>
    @endforeach

    <section class="card">
        <header class="card-header">
            <div class="card-header-action">
                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
            </div>

                <h4 class="card-title">{{{ trans('admin.reply_ticket') }}}</h4>

        </header>
        <div class="card-body">
            <form action="/admin/ticket/reply/store/{{{ $ticket->id or '' }}}" class="form-horizontal form-bordered" method="post">

                <input type="hidden" name="id" value="{{{ $item->id or '' }}}">

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="msg" required>{{{ $item->msg or '' }}}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 text-left">{{{ trans('admin.attachments') }}}</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="attach" class="form-control">
                            <span class="input-group-append click-for-upload cu-p">
                                <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        @if($ticket->mode == 'open')
                            <a class="btn btn-danger pull-right" href="/admin/ticket/close/{{{ $ticket->id or 0 }}}" >{{{ trans('admin.close_ticket') }}}</a>
                        @else
                            <a class="btn btn-success pull-right" href="/admin/ticket/open/{{{ $ticket->id or 0 }}}" >{{{ trans('admin.open_ticket') }}}</a>
                        @endif
                        <button class="btn btn-primary pull-left" type="submit">{{{ trans('admin.send') }}}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
