@extends('view.layout.layout')
@section('title')
    {{{ $setting['site']['site_title'] or '' }}}
    - {{{ $post->title or '' }}}
@endsection

@section('page')

    <div class="container-fluid">
        <div class="container">
            <div class="blog-section">
                    <div class="col-xs-12 row blog-post-box blog-post-box-s">
                        <div class="col-md-4 col-xs-12">
                            <img src="{{{ $post->image or '' }}}" class="img-responsive">
                            <span class="date-section">{{{ date('d F Y',$post->create_at) }}}</span>
                            <span class="date-section date-section-s">
                                <img src="{{{ $post->category->image or '' }}}" class="img-responsive pull-left">
                                <a href="/category/{{{ $post->category->class or '' }}}" class="pull-left a-link-s">{{{ $post->category->title or '' }}}</a>
                            </span>
                            <div class="product-user-box">
                                <?php $userMeta = arrayToList($post->user->usermetas,'option','value'); ?>
                                <img class="img-box" src="{{{ $userMeta['avatar'] or get_option('default_user_avatar','') }}}" class="img-responsive"/>
                                <span>{{{ $post->user->name or '' }}}</span>
                                <div class="user-description-box">
                                    {{{ $userMeta['short_biography'] or '' }}}
                                </div>
                                <div class="text-center">
                                    @foreach($rates as $rate)
                                        <img class="img-icon img-icon-s" src="{{{ $rate['image'] or '' }}}" title="{{{ $rate['description'] or '' }}}"/>
                                    @endforeach
                                </div>
                                    <div class="h-10"></div>
								<div class="product-user-box-footer">
                                <a href="/profile/{{{ $post->user->id or '' }}}">{{{ trans('main.user_profile') }}}</a>
                            </div>
                            </div>

                        </div>
                        <div class="col-md-8 col-xs-12 text-section">
                            <h1 class="text-section-s1">{{{ $post->title or '' }}}</h1>
                            <br>
                            {!!   $post->pre_text or '' !!}
                            <hr>
                            {!!   $post->text or '' !!}
                            <br>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="col-xs-12 col-md-12 article-tabs">
                <div class="user-tabs article-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#vtab1" role="tab" data-toggle="tab">{{{ trans('main.related_courses') }}}</a></li>
                        <li><a href="#vtab2" role="tab" data-toggle="tab">{{{ trans('main.user_courses') }}}</a></li>
                    </ul>
                    <!-- TAB CONTENT -->
                    <div class="tab-content articlec">
                        <div class="active tab-pane fade in tab-body" id="vtab1">
                            @foreach($relContent as $new)
                                <?php $meta = arrayToList($new->metas,'option','value'); ?>
                                <div class="col-md-3 col-sm-6 col-xs-12 tab-con">
                                    <a href="/product/{{{ $new->id or '' }}}" title="{{{ $new->title or '' }}}" class="content-box">
                                        <img src="{{{ $meta['thumbnail'] or '' }}}"/>
										<h3>{!! str_limit($new->title,35,'...') !!}</h3>
                                        <div class="footer">
                                            <label class="pull-right">@if(isset($meta['duration'])){{{ convertToHoursMins($meta['duration']) }}}@else {{{ trans('main.not_defined') }}} @endif </label>
											<span class="boxicon mdi mdi-clock pull-right"></span>
											<span class="boxicon mdi mdi-wallet pull-left"></span>
                                            <label class="pull-left">@if(isset($meta['price']) && $meta['price']>0) {{{ currencySign() }}}{{{$meta['price']}}} @else {{{ trans('main.free') }}} @endif</label>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane fade tab-body" id="vtab2">
                            @foreach($userContent as $new)
                                <?php $meta = arrayToList($new->metas,'option','value'); ?>
                                <div class="col-md-3 col-sm-6 col-xs-12 tab-con">
                                    <a href="/product/{{{ $new->id or '' }}}" title="{{{ $new->title or '' }}}" class="content-box">
                                        <img src="{{{ $meta['thumbnail'] or '' }}}"/>
										<h3>{!! str_limit($new->title,35,'...') !!}</h3>
                                        <div class="footer">
                                            <label class="pull-right">@if(isset($meta['duration'])){{{ convertToHoursMins($meta['duration']) }}}@else {{{ trans('main.not_defined') }}} @endif </label>
											<span class="boxicon mdi mdi-clock pull-right"></span>
											<span class="boxicon mdi mdi-wallet pull-left"></span>
                                            <label class="pull-left">@if(isset($meta['price']) && $meta['price']>0) {{{ currencySign() }}}{{{$meta['price']}}} @else {{{ trans('main.free') }}} @endif</label>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="h-30"></div>

@endsection
