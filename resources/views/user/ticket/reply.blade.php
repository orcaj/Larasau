@extends($user['vendor'] == 1?'user.layout.supportlayout':'user.layout_user.supportlayout')
@section('tab3','active')
@section('tab')

    <div class="h-20"></div>
    <div class="row">
        <div class="col-md-6 col-xs-12 tab-con">
            <div class="ucp-section-box">
                <div class="header back-red">{{{ trans('main.reply') }}}</div>
                <div class="body">
                    <form method="post" action="/user/ticket/reply/store">

                        <input type="hidden" name="ticket_id" value="{{{ $ticket->id }}}">

                        <div class="form-group">
                            <textarea class="form-control" placeholder="Reply..." rows="7" name="msg" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2 control-label-p" >{{{ trans('main.attachment') }}}</label>
                            <div class="input-group">
                                <input type="text" name="attach" class="form-control">
                                <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-custom pull-left" value="Send">{{{ trans('main.send') }}}</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 tab-con">
                @foreach($ticket->messages as $msg)
                    @if($msg->mode == 'user')
                        <div class="ucp-section-box">
                            <div class="header back-blue">{{{ trans('main.user') }}}-{{{ $msg->user->name or '' }}}
                            <span class="pull-left">{{{ date('d F y h:i',$msg->create_at) }}}</span>
                            </div>
                            <div class="body pos-rel">
                                {!! $msg->msg or '' !!}
                                @if($msg->attach != null && $msg->attach != '')
                                    <br>
                                    <a href="{!! $msg->attach or '' !!}" target="_blank" class="pull-left attach-s"><span class="crticon mdi mdi-paperclip"></span>&nbsp;Attachment</a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="ucp-section-box">
                            <div class="header back-green">{{{ trans('main.staff') }}}
                                <span class="pull-left">{{{ date('d F y h:i',$msg->create_at) }}}</span>
                            </div>
                            <div class="body pos-rel">
                                {!! $msg->msg or '' !!}
                                @if($msg->attach != null && $msg->attach != '')
                                    <br>
                                    <a href="{!! $msg->attach or '' !!}" target="_blank" class="pull-left attach-s"><span class="crticon mdi mdi-paperclip"></span>&nbsp;Attachment</a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
    </div>

@endsection
