@extends('admin.newlayout.layout',['breadcom'=>['Advertising','New Banner']])
@section('title')
   {{{ trans('admin.new_banner') }}}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">

            <form action="/admin/ads/box/store" class="form-horizontal form-bordered" method="post">

                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                    <div class="col-md-5">
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.position') }}}</label>
                    <div class="col-md-5">
                        <select name="position" class="form-control">
                            <option value="main-slider-side">{{{ trans('admin.homepage_slider') }}}</option>
                            <option value="main-article-side">{{{ trans('admin.homepage_articles') }}}</option>
                            <option value="category-side">{{{ trans('admin.cat_page_sidebar') }}}</option>
                            <option value="category-pagination-bottom">{{{ trans('admin.cat_page_bottom') }}}</option>
                            <option value="product-page">{{{ trans('admin.product_page') }}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.image') }}}</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-prepend view-selected cursor-pointer" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                            </span>
                            <input type="text" name="image" dir="ltr" class="form-control" required>
                            <span class="input-group-append click-for-upload cursor-pointer">
                                <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.banner_size') }}}</label>
                    <div class="col-md-5">
                        <select name="size" class="form-control">
                            <option value="col-md-12 col-sm-12 col-xs-12">{{{ trans('admin.original') }}}</option>
                            <option value="col-md-6 col-sm-6 col-xs-12">1/2</option>
                            <option value="col-md-4 col-sm-6 col-xs-12">1/3</option>
                            <option value="col-md-3 col-sm-6 col-xs-12">1/4</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.link_address') }}}</label>
                    <div class="col-md-5">
                            <input type="text" name="url" dir="ltr" class="form-control text-left">
                    </div>
                    <div class="h-20"></div>
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.sort') }}}</label>
                    <div class="col-md-5">
                        <input type="number" min="0" max="99" name="sort" dir="ltr" class="form-control text-center">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="custom-switches-stacked">
                            <label class="custom-switch">
                                <input type="hidden" name="mode" value="draft">
                                <input type="checkbox" name="mode" value="publish" class="custom-switch-input" />
                                <span class="custom-switch-indicator"></span>
                                <label class="custom-switch-description" for="inputDefault">{{{ trans('admin.publish_item') }}}</label>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{{ trans('admin.save_changes') }}}</button>
                    </div>
                </div>

            </form>
        </div>
    </section>

@endsection
