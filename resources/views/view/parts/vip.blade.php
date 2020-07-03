<div class="container-fluid newest-container">
        <div class="container">
            <div class="row">
                <div class="header">
                    <span class="popular pull-left feat-s">{!! trans('main.featured') !!}</span>
					<a href="/category" class="more-link pull-right">{{{ trans('main.load_more') }}}</a>
                </div>
                <div class="body body-s-r" dir="ltr">
                    <span class="nav-right"></span>
                    <div class="owl-carousel">
                    @foreach($vip_content as $content)
                        <?php $popular = $content->content; ?>
                        @if(isset($popular->metas))
                            <?php $meta = arrayToList($popular->metas,'option','value'); ?>
                            <div class="owl-car-s" dir="rtl">
                                <a href="/product/{{{ $popular->id or '' }}}" title="{{{ $popular->title or '' }}}" class="content-box">

                                    <span></span>
                                    <img src="{{{ $meta['thumbnail'] or '' }}}"/>
									<h3>{!! str_limit($popular->title,30,'...') !!}</h3>
                                    <div class="footer">
                                        <span class="avatar" title="{{{ $popular->user->name or '' }}}" onclick="window.location.href = '/profile/{{{ $popular->user->id or 0 }}}'"><img src="{{{ get_user_meta($popular->user_id,'avatar',get_option('default_user_avatar','')) }}}"></span>
                                        <label class="pull-right content-clock">@if(isset($meta['duration'])){{{ convertToHoursMins($meta['duration']) }}}@else {{{ trans('main.not_defined') }}} @endif </label>
										<span class="boxicon mdi mdi-clock pull-right"></span>
										<span class="boxicon mdi mdi-wallet pull-left"></span>
                                        <label class="pull-left">@if(isset($meta['price']) && $meta['price']>0) {!! currencySign()!!}{{{ price($popular->id,$popular->category_id,$meta['price'])['price'] }}} @else {{{ trans('main.free') }}} @endif</label>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                    </div>
                    <span class="nav-left pull-right"></span>
                </div>
            </div>
        </div>
</div>
