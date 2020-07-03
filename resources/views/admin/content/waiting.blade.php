@extends('admin.newlayout.layout',['breadcom'=>['Courses','Latest Courses']])
@section('title')
    {{{ trans('admin.list_courses') }}}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" value="{!! $_GET['fsdate'] or '' !!}" id="fsdate" class="text-center form-control" name="fsdate" placeholder="Start Date">
                            <input type="hidden" id="fdate" name="fdate" value="{!! $_GET['fdate'] or '' !!}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" value="{!! $_GET['lsdate'] or '' !!}" id="lsdate" class="text-center form-control" name="lsdate" placeholder="End Date">
                            <input type="hidden" id="ldate" name="ldate" value="{!! $_GET['ldate'] or '' !!}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="user" data-plugin-selectTwo class="form-control populate" >
                                <option value="">{{{ trans('admin.all_users') }}}</option>
                                @foreach($users as $user)
                                    <option value="{{{ $user->id or 0 }}}" @if(isset($_GET['user']) && $_GET['user']==$user->id) selected @endif>{{{ $user->name or $user->username }}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="cat" data-plugin-selectTwo class="form-control populate" >
                                <option value="">{{{ trans('admin.all_categories') }}}</option>
                                @foreach($category as $cat)
                                    <option value="{{{ $cat->id or 0 }}}" @if(isset($_GET['cat']) && $_GET['cat']==$cat->id) selected @endif>{{{ $cat->title or '' }}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 h-15" ></div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="text" class="form-control text-center" name="id" value="{!! $_GET['id'] or '' !!}" placeholder="Item Code">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" class="form-control text-center" name="title" value="{!! $_GET['title'] or '' !!}" placeholder="Course Title">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="order" class="form-control">
                                <option value="">{{{ trans('admin.filter_type') }}}</option>
                                <option value="sella"  @if(isset($_GET['order']) && $_GET['order']=='sella') selected @endif>{{{ trans('admin.sales') }}}-{{{ trans('admin.ascending') }}}</option>
                                <option value="selld"  @if(isset($_GET['order']) && $_GET['order']=='selld') selected @endif>{{{ trans('admin.sales') }}}-{{{ trans('admin.descending') }}}</option>
                                <option value="suma"   @if(isset($_GET['order']) && $_GET['order']=='suma') selected @endif>{{{ trans('admin.sales_amount') }}}-{{{ trans('admin.ascending') }}}</option>
                                <option value="sumd"   @if(isset($_GET['order']) && $_GET['order']=='sumd') selected @endif>{{{ trans('admin.sales_amount') }}}-{{{ trans('admin.descending') }}}</option>
                                <option value="viewd"  @if(isset($_GET['order']) && $_GET['order']=='viewd') selected @endif>{{{ trans('admin.views') }}}-{{{ trans('admin.descending') }}}</option>
                                <option value="viewa"  @if(isset($_GET['order']) && $_GET['order']=='viewa') selected @endif>{{{ trans('admin.views') }}}-{{{ trans('admin.ascending') }}}</option>
                                <option value="pricea" @if(isset($_GET['order']) && $_GET['order']=='pricea') selected @endif>{{{ trans('admin.item_price') }}}-{{{ trans('admin.ascending') }}}</option>
                                <option value="priced" @if(isset($_GET['order']) && $_GET['order']=='priced') selected @endif>{{{ trans('admin.item_price') }}}-{{{ trans('admin.descending') }}}</option>
                                <option value="datea"  @if(isset($_GET['order']) && $_GET['order']=='datea') selected @endif>{{{ trans('admin.th_date') }}}-{{{ trans('admin.ascending') }}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="submit" class="text-center btn btn-primary w-100" value="Apply Filter" >
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th >{{{ trans('admin.th_title') }}}</th>
                    <th class="text-center" width="150">{{{ trans('admin.th_date') }}}</th>
                    <th class="text-center">{{{ trans('admin.th_vendor') }}}</th>
                    <th class="text-center" width="50">{{{ trans('admin.sales') }}}</th>
                    <th class="text-center" width="50">{{{ trans('admin.parts') }}}</th>
                    <th class="text-center">{{{ trans('admin.income') }}}</th>
                    <th class="text-center" width="50">{{{ trans('admin.views') }}}</th>
                    <th class="text-center" width="50">{{{ trans('admin.price') }}}</th>
                    <th class="text-center">{{{ trans('admin.category') }}}</th>
                    <th class="text-center">{{{ trans('admin.type') }}}</th>
                    <th class="text-center" width="50">{{{ trans('admin.th_status') }}}</th>
                    <th class="text-center" width="100">{{{ trans('admin.th_controls') }}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($lists as $item)
                        <?php $meta = arrayToList($item->metas,'option','value'); ?>
                        <tr>
                            <td><a href="/product/{{{ $item->id or 0 }}}" target="_blank">{{{ $item->title  or ''}}}</a></td>
                            <td class="text-center" width="150">{{{ date('d F Y / H:i',$item->create_at) }}}</td>
                            <td class="text-center" title="{{{ $item->user->username or '' }}}"><a href="/admin/user/item/{{{ $item->user->id or '' }}}">{{{ $item->user->name or '' }}}</a></td>
                            <td class="text-center">{{{ $item->sells_count or '0' }}}</td>
                            <td class="text-center">{{{ $item->partsactive_count or '0' }}}</td>
                            <td class="text-center">{{{ $item->transactions->sum('price') }}}<br>{{{ trans('admin.cur_dollar') }}}</td>
                            <td class="text-center">{{{ $item->view or '0' }}}</td>
                            <td class="text-center">{{{ $meta['price'] or 'Free' }}}</td>
                            <td class="text-center">{{{ $item->category->title or '' }}}</td>
                            <td class="text-center">
                                @if($item->private==1)
                                    <b class="c-g">{{{ trans('admin.exclusive') }}}</b>
                                @else
                                    <b class="c-o">{{{ trans('admin.open') }}}</b>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->parts_request_count>0)
                                    <b class="c-o">{{{ trans('admin.pending') }}}</b>
                                @endif
                                @if($item->mode == 'request')
                                    <b class="c-r">{{{ trans('admin.review_request') }}}</b>
                                @elseif($item->mode == 'delete')
                                    <b class="c-r">{{{ trans('admin.unpublish_request') }}}</b>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="/admin/notification/new?recipent_type=userone&uid={{{ $item->user->id or 0 }}}" title="Send notification to user"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
                                <a href="/admin/ticket/new?uid={{{ $item->user->id or 0 }}}&title=Course {{{ $item->title or '' }}}" title="Send support ticket to user"><i class="fa fa-life-ring" aria-hidden="true"></i></a>
                                <a href="/admin/content/edit/{{{ $item->id }}}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a href="#" data-href="/admin/content/delete/{{{ $item->id }}}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-center">
            {!! $lists->appends($_GET)->links('pagination.default') !!}
        </div>
    </section>

@endsection
