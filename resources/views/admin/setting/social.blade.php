@extends('admin.newlayout.layout',['breadcom'=>['Settings','Social Networks']])
@section('title')
    {{{ trans('admin.social_networks') }}}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div id="day" class="tab-pane active">
                <form method="post" action="/admin/setting/social/store" class="form-horizontal form-bordered">

                    <input type="hidden" name="id" value="{{{$item->id or ''}}}">

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                        <div class="col-md-8">
                            <input type="text" name="title" value="{{{$item->title or ''}}}" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.th_icon') }}}</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-prepend cu-p view-selected" data-toggle="modal" data-target="#icon" data-whatever="image">
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                <input type="text" name="icon" dir="ltr" value="{{{$item->icon or ''}}}" class="form-control">
                                <span class="input-group-append click-for-upload cu-p">
                                    <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">{{{ trans('admin.link_address') }}}</label>
                        <div class="col-md-8">
                            <input type="text" name="link" value="{{{$item->link or ''}}}" class="form-control text-left">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.sort') }}}</label>
                        <div class="col-md-2">
                            <input type="number" name="sort" value="{{{$item->sort or ''}}}" class="form-control text-center">
                        </div>
                        <div class="h-20"></div>
                        <div class="col-md-6">
                            <button class="btn btn-primary pull-left" type="submit">{{{ trans('admin.save_changes') }}}</button>
                        </div>
                    </div>


                </form>
                <table class="table table-bordered table-striped mb-none" id="datatable-basic">
                <thead>
                <tr>
                    <th class="text-center" width="60">{{{ trans('admin.th_icon') }}}</th>
                    <th>{{{ trans('admin.th_title') }}}</th>
                    <th class="text-center" width="100">{{{ trans('admin.link_address') }}}</th>
                    <th class="text-center" width="150">{{{ trans('admin.th_controls') }}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($lists as $list)
                       <tr>
                           <td class="text-center"><img src="{{{ $list->icon or '' }}}" width="24" /></td>
                           <td>{{{ $list->title or '' }}}</td>
                           <td class="text-center"><a href="{{{ $list->link or 0 }}}" target="_blank">{{{ trans('admin.view') }}}</a></td>
                           <td class="text-center">
                               <a href="/admin/setting/social/edit/{{{ $list->id }}}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                               <a href="#" data-href="/admin/setting/social/delete/{{{ $list->id }}}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                           </td>
                       </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
@endsection

