@extends('admin.newlayout.layout',['breadcom'=>['Courses','Categories']])
@section('title')
    {{{ trans('admin.categories') }}}
@endsection
@section('page')
    <div class="card">
        <div class="card-body">
            <div class="tabs">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#list" data-toggle="tab"> {{{ trans('admin.categories') }}} </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#newitem" data-toggle="tab">{{{ trans('admin.new_category') }}}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="list" class="tab-pane active">
                        <table class="table table-bordered table-striped mb-none" id="datatable-details">
                            <thead>
                            <tr>
                                <th width="36">{{{ trans('admin.th_icon') }}}</th>
                                <th>{{{ trans('admin.th_title') }}}</th>
                                <th class="text-center" width="100">{{{ trans('admin.link_title') }}}</th>
                                <th class="text-center" width="100">{{{ trans('admin.th_commission') }}}</th>
                                <th class="text-center" width="80">{{{ trans('admin.subcategories') }}}</th>
                                <th class="text-center" width="80">{{{ trans('admin.cat_filters') }}}</th>
                                <th class="text-center" width="100">{{{ trans('admin.th_controls') }}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $list)
                                <tr>
                                    <td class="text-center"><img src="{{{ $list->image }}}" class="w-24 h-a" /></td>
                                    <td>{{{ $list->title }}}</td>
                                    <td class="text-center">{{{ $list->class or '' }}}</td>
                                    <td class="text-center">{{{ $list->commision }}}</td>
                                    <td class="text-center"><a href="/admin/content/category/childs/{{{ $list->id or '' }}}">{{{ $list->childs_count or '0' }}}</a></td>
                                    <td class="text-center"><a href="/admin/content/category/filter/{{{ $list->id or '' }}}">{{{ $list->filters_count or '0' }}}</a></td>
                                    <td class="text-center">
                                        <a href="/admin/content/category/edit/{{{ $list->id }}}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="/admin/content/category/filter/{{{ $list->id }}}" title="Manage Filters"><i class="fa fa-tags" aria-hidden="true"></i></a>
                                        <a href="#" data-href="/admin/content/category/delete/{{{ $list->id }}}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="newitem" class="tab-pane ">
                        <form method="post" action="/admin/content/category/store" class="form-horizontal form-bordered">

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.parrent_category') }}}</label>
                                <div class="col-md-6">
                                    <select name="parent_id" class="form-control">
                                        <option value="0">{{{ trans('admin.main_category') }}} {{{ trans('admin.select_if') }}}</option>
                                        @foreach($lists as $parent)
                                            <option value="{{{ $parent->id or '' }}}">{{{ $parent->title or '' }}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                                <div class="col-md-6">
                                    <input type="text" name="class" value="" required class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.link_title') }}}</label>
                                <div class="col-md-6">
                                    <input type="text" name="title" required placeholder="Enter SEO friendly URL" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{{ trans('admin.menu_icon') }}}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image" >
                                            <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                        </span>
                                        <input type="text" name="image" required placeholder="Displays on top menu (80*80px)" dir="ltr" class="form-control">
                                        <span class="input-group-append click-for-upload cu-p">
                                            <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{{ trans('admin.cat_page_icon') }}}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="icon">
                                            <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                        </span>
                                        <input type="text" name="icon" dir="ltr" required placeholder="Displays on category page header (80*80px)" class="form-control">
                                        <span class="input-group-append click-for-upload cu-p">
                                            <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{{ trans('admin.cat_page_bg') }}}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="background">
                                            <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                        </span>
                                        <input type="text" name="background" dir="ltr" placeholder="Displays as category page header background (1920*40px) " class="form-control">
                                        <span class="input-group-append click-for-upload cu-p">
                                            <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{{ trans('admin.cat_app_icon') }}}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="background">
                                            <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                        </span>
                                        <input type="text" name="app_icon" dir="ltr" placeholder="App icon (36 x 36) png only" class="form-control">
                                        <span class="input-group-append click-for-upload cu-p">
                                            <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.color_code') }}}</label>
                                <div class="col-md-6">
                                    <input type="text" name="color" value="" required placeholder="eg #e91e63" min class="form-control text-center">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{{{ trans('admin.request_icon') }}}</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="req_icon">
                                            <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                        </span>
                                        <input type="text" name="req_icon" dir="ltr" placeholder="Displays as requests thumbnail (510*310px)" class="form-control">
                                        <span class="input-group-append click-for-upload cu-p">
                                            <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.extra_commission') }}}</label>
                                <div class="col-md-6">
                                    <input type="number" name="commision" min="0" max="100" value="{{{$item->commision or 0}}}" placeholder="%" class="form-control text-center">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
                                    <button class="btn btn-primary" type="submit">{{{ trans('admin.save_changes') }}}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

