@extends($user['vendor'] == 1?'user.layout.balancelayout':'user.layout_user.balancelayout')

@section('tab2','active')
@section('tab')
    <div class="h-20"></div>
    @if(count($lists) == 0)
        <div class="text-center">
            <img src="/assets/images/empty/Postal.png">
            <div class="h-20"></div>
            <span class="empty-first-line">{{{ trans('main.not_found') }}}</span>
            <div class="h-20"></div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table ucp-table" id="post-table">
                <thead class="back-orange">
                <th class="cell-ta">{{{ trans('main.title') }}}</th>
                <th class="cell-ta" width="100">{{{ trans('main.customer') }}}</th>
                <th class="cell-ta" width="100">{{{ trans('main.address') }}}</th>
                <th class="text-center" width="200">{{{ trans('main.tracking_code') }}}</th>
                <th class="text-center" width="200">{{{ trans('main.date') }}}</th>
                <th class="text-center" width="100">{{{ trans('main.income') }}}</th>
                <th class="text-center" width="100">{{{ trans('main.status') }}}</th>
                <th class="text-center" width="50">{{{ trans('main.controls') }}}</th>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td class="cell-ta"><a href="/product/{{{ $item->content->id or 0 }}}" class="color-in" target="_blank">{{{ $item->content->title or '' }}}</a></td>
                        <td class="cell-ta"><a href="/profile/{{{ $item->buyer->id or 0 }}}" class="color-in" target="_blank">{{{ $item->buyer->name or $item->buyer->username }}}</a></td>
                        <td class="cell-ta" title="{{{ $item->buyer->address or '' }}}">
                            @if($item->type == 'post')
                                <span class="img-icon-s" data-toggle="modal" data-target="#address{{{ $item->id or 0 }}}">{{{ trans('main.view') }}}</span>
                                <div class="modal fade" id="address{{{ $item->id or 0 }}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                    &times;
                                                </button>
                                                <h4 class="modal-title">{{{ trans('main.address') }}}</h4>
                                            </div>
                                            <div class="modal-body form-horizontal">
                                                <div class="form-group">
                                                    <label class="control-label col-md-2 text-right ta-ri">{{{ trans('main.province') }}}</label>
                                                    <label class="control-label col-md-10 ta-ri">{{{ userMeta($item->buyer_id,'state','') }}}</label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2 text-right ta-ri"> {{{ trans('main.city') }}}</label>
                                                    <label class="control-label col-md-10 ta-ri">{{{ userMeta($item->buyer_id,'city','') }}}</label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2 text-right ta-ri"> {{{ trans('main.address') }}}</label>
                                                    <label class="control-label col-md-10 ta-ri">{{{ userMeta($item->buyer_id,'address','') }}}</label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-2 text-right ta-ri"> {{{ trans('main.zip_code') }}}</label>
                                                    <label class="control-label col-md-10 ta-ri">{{{ userMeta($item->buyer_id,'postalcode','') }}}</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{{ trans('main.close') }}}</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($item->type == 'post')
                                <form method="post" action="/user/balance/sell/post/setPostalCode">
                                    <input type="hidden" name="sell_id" value="{{{ $item->id or 0 }}}">
                                    <input type="text" class="form-control text-center" name="post_code" value="{{{ $item->post_code or '' }}}">
                            @endif
                        </td>
                        <td class="text-center" width="150">{{{ date('d F Y | H:i',$item->create_at) }}}</td>
                        <td class="text-center">{{{ currencySign() }}}{{{ $item->transaction->income or 0 }}}</td>
                        <td class="text-center">
                            @if($item->post_feedback == null)
                                <b>{{{ trans('main.waiting') }}}</b>
                            @elseif($item->post_feedback == 1)
                                <b class="green-s">{{{ trans('main.successful') }}}</b>
                            @elseif($item->post_feedback == 2 || $item->post_feedback == 3)
                                <b class="red-s">{{{ trans('main.failed') }}}</b>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->type == 'post')
                                <button class="btn btn-custom pull-left" type="submit">{{{ trans('main.submit_tracking_code') }}}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
        @if(!isset($_GET['p']) && $count>20)
            <a href="?p=1" class="next-pagination pull-left"><span class="pagicon mdi mdi-chevron-left"></span></a>
        @endif
        @if(isset($_GET['p']) && $count>($_GET['p']+1)*20)
            <a href="?p={{{ $_GET['p']+1 }}}" class="next-pagination pull-left"><span class="pagicon mdi mdi-chevron-left"></span></a>
        @endif
        @if(isset($_GET['p']) && $_GET['p']>0)
            <a href="?p={{{ $_GET['p']-1 }}}" class="next-pagination pull-right"><span class="pagicon mdi mdi-chevron-right"></span></a>
        @endif
    </div>
    @endif
@endsection
