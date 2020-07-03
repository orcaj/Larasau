@extends('admin.newlayout.layout',['breadcom'=>['Courses','Category','Edit']])
@section('title')
    {{{ trans('admin.th_edit') }}} {{{ trans('admin.category') }}}
@endsection
@section('page')

    <div class="tabs">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="#list" class="nav-link" data-toggle="tab"> {{{ trans('admin.categories') }}} </a>
            </li>
            <li class="nav-item">
                <a href="#newitem" class="nav-link" data-toggle="tab">{{{ trans('admin.new_category') }}}</a>
            </li>
            <li class="nav-item">
                <a href="#edititem" class="nav-link active" data-toggle="tab">{{{ trans('admin.th_edit') }}}</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="list" class="tab-pane ">
            <table class="table table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th width="36">{{{ trans('admin.th_icon') }}}</th>
                    <th>{{{ trans('admin.th_title') }}}</th>
                    <th class="text-center" width="100">{{{ trans('admin.link_title') }}}</th>
                    <th class="text-center" width="100">{{{ trans('admin.th_commission') }}}</th>
                    <th class="text-center" width="150">{{{ trans('admin.subcategories') }}}</th>
                    <th class="text-center" width="100">{{{ trans('admin.th_controls') }}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($lists as $list)
                       <tr>
                           <td class="text-center"><img src="{{{ $list->image }}}" class="w-24 h-a"/></td>
                           <td>{{{ $list->title }}}</td>
                           <td class="text-center">{{{ $list->class or '' }}}</td>
                           <td class="text-center">{{{ $list->commision }}}</td>
                           <td class="text-center"><a href="/admin/content/category/childs/{{{ $list->id or '' }}}">{{{ $list->childs_count or '0' }}}</a></td>
                           <td class="text-center">
                               <a href="/admin/content/category/edit/{{{ $list->id }}}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                               <a href="/admin/content/category/filter/{{{ $list->id }}}" title="Category Filters"><i class="fa fa-tags" aria-hidden="true"></i></a>
                               <a href="#" data-href="/admin/content/category/delete/{{{ $list->id }}}" title="Edit" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
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
                                <option value="0">{{{ trans('admin.main_category') }}}</option>
                                @foreach($lists as $parent)
                                    <option value="{{{ $parent->id or '' }}}">{{{ $parent->title or '' }}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                        <div class="col-md-6">
                            <input type="text" name="title" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.link_title') }}}</label>
                        <div class="col-md-6">
                            <input type="text" name="class" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{{{ trans('admin.menu_icon') }}}</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image" >
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                <input type="text" name="image" dir="ltr" class="form-control">
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
                                <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="icon" >
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                <input type="text" name="icon" dir="ltr" class="form-control">
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
                                <input type="text" name="background" dir="ltr" class="form-control">
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
                            <input type="text" name="color" value="" class="form-control text-center">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.th_commission') }}}</label>
                        <div class="col-md-6">
                            <input type="number" name="commision" min="0" max="100" value="" placeholder="%" class="form-control text-center">
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
            <div id="edititem" class="tab-pane active">
                <form method="post" action="/admin/content/category/store" class="form-horizontal form-bordered">

                    <input type="hidden" name="edit" value="{{{ $item->id or '' }}}">

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.parrent_category') }}}</label>
                        <div class="col-md-6">
                            <select name="parent_id" class="form-control">
                                <option value="0">{{{ trans('admin.main_category') }}}</option>
                                @foreach($lists as $parent)
                                    <option value="{{{ $parent->id or '' }}}"  @if($parent->id == $item->parent_id) {{{ 'selected' }}} @endif>{{{ $parent->title or '' }}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                        <div class="col-md-6">
                            <input type="text"  name="title" value="{{{ $item->title or '' }}}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.link_title') }}}</label>
                        <div class="col-md-6">
                            <input type="text" name="class" value="{{{ $item->class or '' }}}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{{{ trans('admin.menu_icon') }}}</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                <input type="text" name="image" dir="ltr" value="{{{ $item->image or '' }}}" class="form-control">
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
                                <input type="text" name="icon" value="{{{ $item->icon or '' }}}" dir="ltr" class="form-control">
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
                                <input type="text" name="background" value="{{{ $item->background or '' }}}" dir="ltr" class="form-control">
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
                                <input type="text" name="app_icon" dir="ltr" value="{!! $item->app_icon or '' !!}" placeholder="App icon (36 x 36) png only" class="form-control">
                                <span class="input-group-append click-for-upload cu-p">
                                            <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.color_code') }}}</label>
                        <div class="col-md-6">
                            <input type="text" name="color" value="{{{ $item->color or '' }}}" class="form-control text-center">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{{{ trans('admin.request_icon') }}}</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="req_icon">
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                <input type="text" name="req_icon" value="{{{ $item->req_icon or '' }}}" dir="ltr" class="form-control">
                                <span class="input-group-append click-for-upload cu-p">
                                    <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">{{{ trans('admin.th_commission') }}}</label>
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
@endsection

