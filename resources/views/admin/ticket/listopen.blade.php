@extends('admin.newlayout.layout',['breadcom'=>['Support','Pending Tickets']])
@section('title')
    {{{ trans('admin.pending_tickets') }}}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" id="fsdate" class="text-center form-control" name="fsdate" value="{!! $_GET['fsdate'] or '' !!}" placeholder="Start Date">
                            <input type="hidden" id="fdate" name="fdate" value="{!! $_GET['fdate'] or '' !!}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="date" id="lsdate" class="text-center form-control" name="lsdate" value="{!! $_GET['lsdate'] or '' !!}" placeholder="End Date">
                            <input type="hidden" id="ldate" name="ldate" value="{!! $_GET['ldate'] or '' !!}">
                            <span class="input-group-addon ldatebtn" id="ldatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="user" data-plugin-selectTwo class="form-control populate">
                                <option value="">{{{ trans('admin.all_users') }}}</option>
                                @foreach($users as $user)
                                    <option value="{{{ $user->id or 0 }}}" @if(isset($_GET['user']) && $_GET['user']==$user->id) selected @endif>{{{ $user->username or $user->name }}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="submit" class="text-center btn btn-primary w-100" value="Show Results">
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
                    <th class="text-center">{{{ trans('admin.created_date') }}}</th>
                    <th class="text-center">{{{ trans('admin.last_update') }}}</th>
                    <th class="text-center">{{{ trans('admin.username') }}}</th>
                    <th class="text-center">{{{ trans('admin.invited_users') }}}</th>
                    <th class="text-center">{{{ trans('admin.department') }}}</th>
                    <th class="text-center">{{{ trans('admin.th_status') }}}</th>
                    <th class="text-center">{{{ trans('admin.th_controls') }}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($lists as $item)
                        <tr>
                            <td><a href="/admin/ticket/reply/{{{ $item->id }}}">{{{ $item->title }}}</a></td>
                            <td class="text-center">{{{ date('d F Y : H:i',$item->create_at) }}}</td>
                            <td class="text-center">{{{ date('d F Y : H:i',$item->update_at) }}}</td>
                            <td class="text-center">
                                <a title="{{{ $item->user->name or '' }}}" href="/profile/{{{ $item->user->id or 0 }}}">{{{ $item->user->username or '' }}}</a>
                            </td>
                            <td class="text-center">
                                @if($item->users != null)
                                    @foreach($item->users as $u)
                                        <a title="{{{ $u->user->name or '' }}}" href="/profile/{{{ $u->user->id or 0 }}}">{{{ $u->user->username or '' }}}</a>
                                        <br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">{{{ $item->category->title or '' }}}</td>
                            <td class="text-center">
                                @if($item->mode == 'open')
                                    <b class="f-w-b">{{{ trans('admin.pending') }}}</b>
                                @elseif($item->mode == 'admin')
                                    <b class="c-g">{{{ trans('admin.replied') }}}</b>
                                @else
                                    <b class="c-r">{{{ trans('admin.closed') }}}</b>
                                @endif
                            </td>
                            <td class="text-center" width="50">
                                <a href="/admin/ticket/user/{{{ $item->id }}}" title="Add user to conversation"><i class="fa fa-user" aria-hidden="true"></i></a>
                                <a href="/admin/ticket/reply/{{{ $item->id }}}" title="Reply"><i class="fa fa-reply" aria-hidden="true"></i></a>
                                <a href="#" data-href="/admin/ticket/delete/{{{ $item->id }}}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
