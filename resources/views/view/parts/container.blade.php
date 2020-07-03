<div class="container hidden-xs hidden-sm" id="anchor-animate">
    <div class="h-25"></div>
    <div class="row">
        <div class="col-xs-12 col-md-4 container-banner-section">
            @if(isset($ads))
                @foreach($ads as $ad)
                    @if($ad->position == 'main-slider-side')
                        <a href="{{{ $ad->url or '#' }}}"><img src="{{{ $ad->image or '' }}}" class="{{{ $ad->size or '' }}}"></a>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="col-xs-12 col-md-8 parts-container">
            @if(!empty($slider_container))
                @foreach($slider_container as $slide)
                    @if(isset($slide->content->metas))
                        <?php $slide_meta = arrayToList($slide->content->metas,'option','value'); ?>
                        <div class="parts-container-slide" id="slide{{{ $slide->content->id or 0 }}}">
                        <div class="header">
                            <span>{{{ trans('main.featured') }}}</span>
                            <h2><a href="/product/{{{ $slide->content->id or 0 }}}">{{{ $slide->content->title or '' }}}</a></h2>
                        </div>
                        <div class="body-container">
                            <div class="row">
                                <div class="col-md-7">
                                    <img src="{{{ $slide_meta['cover'] or '' }}}" class="img-responsive img-main-container img-con-r">
                                </div>
                                <div class="col-md-5 img-con-s">
                                    <div class="item-container">
                                        <div class="col-md-10 text-item">
                                            <span>{{{ $slide->description or '' }}}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="homeicon mdi mdi-comment"></span>
                                        </div>
                                    </div>
                                    <div class="item-container">
                                        <div class="col-md-10 timer-item">
                                            <label>{{{ $slide_meta['duration'] or 0 }}} {{{ trans('main.min') }}}</label>
                                        </div>
                                        <div class="col-md-2 ">
                                            <span class="homeicon mdi mdi-alarm"></span>
                                        </div>
                                    </div>
                                    <div class="item-container">
                                        <div class="col-md-10 price-item">
                                            @if(isset($slide_meta['price']) && $slide_meta['price']>0)
                                                <label>{{{currencySign()}}}{{{ $slide_meta['price'] or 0 }}}</label>
                                            @else
                                                <label>{{{ trans('main.free_item') }}}</label>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <span class="homeicon mdi mdi-wallet"></span>
                                        </div>
                                    </div>
                                    <div class="item-container">
                                        <div class="col-md-10 price-item profile-item">
                                            <label><a href="/profile/{{{ $slide->content->user->id or 0 }}}" class="profile-s">{{{ $slide->content->user->name or '' }}}</a></label>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="homeicon mdi mdi-teach"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="/product/{{{ $slide->content->id or 0 }}}" class="btn btn-container-more btn-container-more-s">{{{ trans('main.view_product') }}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif
                <div class="col-xs-12">
                    <ul class="container-bullet">
                        @if(!empty($slider_container))
                            @foreach($slider_container as $index=>$slide)
                                <li data-target="slide{{{ $slide->content->id or 0 }}}" @if($index == 0) class="active" @endif></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
        </div>
        </div>
    <div class="h-25"></div>
</div>
