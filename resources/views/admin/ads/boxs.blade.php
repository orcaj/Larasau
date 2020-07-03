@extends('admin.newlayout.layout',['breadcom'=>['Advertising','Banners']])
@section('title')
   {{{ trans('admin.banners_list') }}}
@endsection
@section('page')

    <div class="card card-primary">
        <div class="card-body">
            <form method="post" action="/admin/setting/store">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{{ trans('admin.top_left') }}}</label>
                            <div class="input-group">
                                <span class="input-group-prepend view-selected ads-boxs-1" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                <input type="text" name="triangle-banner-top-image" value="{{{ get_option('triangle-banner-top-image') }}}" dir="ltr" class="form-control">
                                <span class="input-group-append click-for-upload ads-boxs-1">
                                    <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{{ trans('admin.top_left_link') }}}</label>
                            <input type="text" class="form-control" name="triangle-banner-top-url" value="{{{ get_option('triangle-banner-top-url') }}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{{ trans('admin.bottom_left') }}}</label>
                            <div class="input-group">
                                <span class="input-group-prepend view-selected ads-boxs-1" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                    <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </span>
                                <input type="text" name="triangle-banner-bottom-image" value="{{{ get_option('triangle-banner-bottom-image') }}}" dir="ltr" class="form-control">
                                <span class="input-group-append click-for-upload ads-boxs-1">
                                    <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{{ trans('admin.bottom_left_link') }}}</label>
                            <input type="text" class="form-control" name="triangle-banner-bottom-url" value="{{{ get_option('triangle-banner-bottom-url') }}}">
                        </div>
                    </div>
                </div>
                <div class="h-15"></div>
                <div class="form-group">
                    <label>{{{ trans('admin.bottom_sticky') }}}</label>
                    <textarea class="form-control text-left" dir="ltr" rows="10" name="banner-html-box">{!! get_option('banner-html-box','') !!}</textarea>
                </div>
                <div class="form-group">
                    <div class="h-15"></div>
                    <input type="submit" value="Save Changes" class="btn btn-primary pull-left">
                </div>
            </form>
        </div>
    </div>
    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{{ trans('admin.th_title') }}}</th>
                    <th class="text-center">{{{ trans('admin.position') }}}</th>
                    <th class="text-center">{{{ trans('admin.banner_size') }}}</th>
                    <th class="text-center" width="100">{{{ trans('admin.th_status') }}}</th>
                    <th class="text-center" width="100">{{{ trans('admin.th_controls') }}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($lists as $list)
                        <tr>
                            <th >{{{ $list->title or '' }}}</th>
                            <th class="text-center">
                                <?php
                                switch($list->position){
                                    case('main-slider-side'):
                                        echo 'Homepage-Beside Slider';
                                        break;
                                    case('main-article-side'):
                                        echo 'Homepage-Beside Articles';
                                        break;
                                    case('category-side'):
                                        echo 'Category Page-Sidebar';
                                        break;
                                    case('category-pagination-bottom'):
                                        echo 'Category Page-Bottom';
                                        break;
                                    case('product-page'):
                                        echo 'Product Page-Sidebar';
                                        break;
                                }
                                ?>
                            </th>
                            <th class="text-center" width="200">
                                @if($list->size=='col-md-12 col-sm-12 col-xs-12')
                                    {{{ 'Original' }}}
                                @elseif($list->size=='col-md-6 col-sm-6 col-xs-12')
                                    {{{ '1/2' }}}
                                @elseif($list->size=='col-md-4 col-sm-6 col-xs-12')
                                    {{{ '1/3' }}}
                                @elseif($list->size=='col-md-3 col-sm-6 col-xs-12')
                                    {{{ '1/4' }}}
                                @endif
                            </th>
                            <th class="text-center">
                                @if($list->mode == 'publish')
                                    <span class="color-green">{{{ trans('admin.active') }}}</span>
                                @elseif($list->mode == 'draft')
                                    <span class="color-orange">{{{ trans('admin.disabled') }}}</span>
                                @endif
                            </th>
                            <th class="text-center">
                                <a href="/admin/ads/box/edit/{{{ $list->id }}}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a href="#" data-href="/admin/ads/box/delete/{{{ $list->id }}}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection


