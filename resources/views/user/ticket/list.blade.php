@extends($user['vendor'] == 1?'user.layout.supportlayout':'user.layout_user.supportlayout')

@section('tab3','active')
@section('tab')

    <div class="h-20"></div>
    <div class="row">
        <div class="col-md-6 col-xs-12 tab-con">
            <div class="ucp-section-box">
                <div class="header back-red">{{{ trans('main.new_support_ticket') }}}</div>
                <div class="body">
                    <form method="post" action="/user/ticket/store">

                        <div class="form-group">
                            <label class="control-label" for="inputDefault">{{{ trans('main.title') }}}</label>
                            <input type="text" name="title" class="form-control" id="inputDefault" @if(isset($_GET['type']) && $_GET['type'] == 'become_vendor') value="{!! trans('main.become_vendor_title') !!}" disabled @endif required>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="inputDefault">{{{ trans('main.department') }}}</label>
                            <select name="category_id" class="form-control font-s" required>
                                @foreach($category as $cat)
                                    <option value="{{{ $cat->id or '' }}}">{{{ $cat->title or '' }}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label control-label-p">{{{ trans('main.attachment') }}}</label>
                            <div class="input-group">
                                <input type="text" name="attach" class="form-control text-left" dir="ltr">
                                <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{{{ trans('main.description') }}}</label>
                            <textarea class="form-control" rows="7" name="msg" required></textarea>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-custom pull-left" value="Reply">{{{ trans('main.send') }}}</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 tab-con">
            @if(count($lists) == 0)
                <div class="text-center">
                    <img src="/assets/images/empty/tickets.png">
                    <div class="h-20"></div>
                    <span class="empty-first-line">{{{ trans('main.no_sup_ticket') }}}</span>
                    <div class="h-20"></div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table ucp-table" id="ticket-table">
                        <thead class="back-blue">
                            <tr>
                                <th class="cell-ta">{{{ trans('main.title') }}}</th>
                                <th width="100" class="text-center">{{{ trans('main.status') }}}</th>
                                <th width="100" class="text-center">{{{ trans('main.controls') }}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lists as $item)
                                <tr>
                                    <td class="cell-ta">{{{ $item->title or '' }}}</td>
                                    <td class="text-center">
                                        @if($item->mode == 'open')

                                            @if($item->messages->last()['mode'] == 'admin')
                                                {{{ 'Staff Reply' }}}
                                            @else
                                                {{{ 'Waiting' }}}
                                            @endif

                                        @else
                                            {{{ 'Closed' }}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/user/ticket/reply/{{{ $item->id }}}" title="View/Reply"><span class="crticon mdi mdi-message-text"></span></a>
                                        @if($item->mode == 'open')
                                            <a href="/user/ticket/close/{{{ $item->id }}}" title="Close Ticket"><span class="crticon mdi mdi-close-octagon"></span></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection
