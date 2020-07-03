@extends($user['vendor'] == 1?'user.layout.videolayout':'user.layout_user.videolayout')

@section('tab4','active')
@section('tab')

    <div class="accordion-off col-xs-12">

        <ul id="accordion" class="accordion off-filters-li">
            <li @if(isset($discount->id)) class="open" @endif>
                <div class="link"><h2>{{{ trans('main.new_discount') }}}</h2><i class="mdi mdi-chevron-down"></i></div>
                <ul class="submenu" @if(isset($discount->id)) style="display: block;" @endif>
                    <div class="h-10"></div>
                    <form method="post" @if(isset($discount->id)) action="/user/video/off/edit/store/{{{ $discount->id or 0 }}}" @else action="/user/video/off/store" @endif class="form form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-1 tab-con">{{{ trans('main.course') }}}</label>
                            <div class="col-md-3 tab-con">
                                <select name="off_id" class="form-control font-s">
                                    @foreach($userContent as $uc)
                                        @if(contentMeta($uc->id,'price',0)>0)
                                            <option value="{{{ $uc->id or 0 }}}" @if(isset($discount->off_id) && $discount->off_id == $uc->id) selected @endif>{{{ $uc->title or '' }}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <label class="control-label col-md-1 tab-con">{{{ trans('main.amount') }}}</label>
                            <div class="col-md-3 tab-con">
                                <input type="number" name="off" value="{{{ $discount->off or '' }}}" class="form-control text-center" min="1" max="99" placeholder="Percent (eg. 20 for 20%)" required>
                            </div>
                            <label class="control-label col-md-1 tab-con"></label>
                            <div class="col-md-3 tab-con">

                            </div>
                        </div>
                        <div class="h-10"></div>
                        <div class="form-group">
                            <label class="control-label col-md-1 tab-con">{{{ trans('main.start_date') }}}</label>
                            <div class="col-md-3 tab-con">
                                <input type="date" class="form-control" name="first_date" id="first_date" value="@if(isset($discount->first_date)) {{{date('d-m-Y',$discount->first_date)}}} @endif" required>
                            </div>
                            <label class="control-label col-md-1 tab-con">{{{ trans('main.end_date') }}}</label>
                            <div class="col-md-3 tab-con">
                                <input type="date" class="form-control" name="last_date" id="last_date" value="@if(isset($discount->last_date)) {{{date('d-m-Y',$discount->last_date)}}} @endif" required>
                            </div>
                            <label class="control-label col-md-1 tab-con"></label>
                            <div class="col-md-3 tab-con">
                                <button type="submit" class="btn btn-custom pull-left col-md-12"><span>{{{ trans('main.save_changes') }}}</span></button>
                            </div>
                        </div>
                    </form>
                    <div class="h-10"></div>
                </ul>
            </li>
            <li class="open">
                <div class="link"><h2>{{{ trans('main.discounts_list') }}}</h2><i class="mdi mdi-chevron-down"></i></div>
                <ul class="submenu dblock">
                    <div class="h-10"></div>
                    @if(count($discounts) == 0)
                        <div class="text-center">
                            <img src="/assets/images/empty/discount.png">
                            <div class="h-20"></div>
                            <span class="empty-first-line">{{{ trans('main.no_discount') }}}</span>
                            <div class="h-10"></div>
                            <span class="empty-second-line">
                                {{{ trans('main.discount_desc') }}}
                            </span>
                            <div class="h-20"></div>
                        </div>
                    @else
                        <div class="table-responsive">
                        <table class="table ucp-table" id="off-table">
                            <thead class="thead-s">
                            <th class="cell-ta">{{{ trans('main.course') }}}</th>
                            <th class="text-center" width="100">{{{ trans('main.start_date') }}}</th>
                            <th class="text-center" width="100">{{{ trans('main.end_date') }}}</th>
                            <th class="text-center" width="100">{{{ trans('main.amount') }}}</th>
                            <th class="text-center" width="100">{{{ trans('main.status') }}}</th>
                            <th class="text-center" width="100">{{{ trans('main.controls') }}}</th>
                            </thead>
                            <tbody>
                            @foreach($discounts as $item)
                                <tr class="text-center">
                                    <td class="cell-ta">{{{ $item->content->title or '' }}}</td>
                                    <td>{{{ date('d F Y',$item->first_date) }}}</td>
                                    <td>{{{ date('d F Y',$item->last_date) }}}</td>
                                    <td>%{{{ $item->off or 0 }}}</td>
                                    <td>
                                        @if($item->mode == "publish")
                                            <b class="green-s">{{{ trans('main.active') }}}</b>
                                        @else
                                            <b class="orange-s">{{{ trans('main.waiting') }}}</b>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="gray-s" href="/user/video/off/edit/{{{ $item->id or 0 }}}" title="Edit"><span class="crticon mdi mdi-lead-pencil"></span></a>
                                        <a class="gray-s" href="/user/video/off/delete/{{{ $item->id or 0 }}}" onclick="return confirm('Are you sure to delete item?');" title="Delete"><span class="crticon mdi mdi-delete-forever"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </ul>
            </li>
        </ul>


    </div>


@endsection
@section('script')
    <script>$('#buy-hover').addClass('item-box-active');</script>
@endsection
