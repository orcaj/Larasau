@extends('view.layout.layout')
@section('title')
    {!! $setting['site']['site_title'].'Profile-'.$profile->name !!}
@endsection
@section('page')

<?php $meta = arrayToList($profile->usermetas,'option','value'); ?>

<div class="container-fluid profile-top-background" style="background: url('{{{ $meta['profile_image'] or get_option('default_user_cover','') }}}');">
    <div class="col-md-3 col-xs-12">

    </div>
    <div class="col-md-9 col-xs-12 bottom-section">
            <span>
                <label class="profile-name">{{{ $profile->name or '' }}}</label>
            @if($follow == 0)
                <a class="btn btn-red btn-hover-animate" href="/follow/{{{ $profile->id or '' }}}"><span class="homeicon mdi mdi-plus"></span>&nbsp;&nbsp;{{{ trans('main.follow') }}}</a>
            @else
                <a class="btn btn-red btn-hover-animate" href="/unfollow/{{{ $profile->id or '' }}}"><span class="homeicon mdi mdi-close"></span>&nbsp;&nbsp;{{{ trans('main.unfollow') }}}</a>
            @endif
                <label class="buttons"><span class="homeicon mdi mdi-account-heart"></span><p>{{{ $follow_count or '0' }}}&nbsp;{{{ trans('main.followers') }}}</p></label>
                <label class="buttons"><span class="homeicon mdi mdi-library-video"></span><p>{!! count($videos) !!} {{{ trans('main.courses') }}}</p></label>
                <label class="buttons"><span class="homeicon mdi mdi-clock"></span><p class="duration-f">{{{ $duration or '0' }}}&nbsp;{{{ trans('main.minutes_stat') }}}</p></label>
    </div>
</div>

<div class="container-fluid profile-middle-background">
    <div class="container">
        <div class="col-md-2 col-xs-12 profile-avatar-container tab-con">
            <img class="sbox3" src="{{{ $meta['avatar'] or get_option('default_user_avatar','') }}}"/>
            <div class="rate-section raty"></div>
        </div>
        <div class="location-section col-md-10 col-xs-12">
            <div class="profile_name_item"><b>{{{ $profile->name or '' }}}</b></div>
            <div class="profile_register_date_item"><b>{{{ trans('main.registration_date') }}}: {{{ date('d F Y',$profile->create_at) }}}</b></div>
        </div>
    </div>
</div>

<div class="h-10"></div>

<div class="container-fluid bg-gray-s">
        <div class="row ucp-menu-item">
            <div class="container">
                <div class="seven-cols">
                    <div class="col-md-1 col-sm-6 col-xs-6 tab-con">
                        <a href="javascript:void(0)" tab-id="t-biography" class="item-box sbox3 arrow_box">
                            <span class="micon mdi mdi-account-tie"></span>
                            <span>{{{ trans('main.profile') }}}</span>
                        </a>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-6 tab-con">
                        <a href="javascript:void(0)" tab-id="t-videos" class="item-box sbox3">
                            <span class="micon mdi mdi-library-video"></span>
                            <span>{{{ trans('main.courses') }}}</span>
                        </a>
                    </div>
                    <div class="h-10 visible-xs"></div>
                    <div class="col-md-1 col-sm-6 col-xs-6 tab-con">
                        <a href="javascript:void(0)" tab-id="t-channels" class="item-box sbox3">
                            <span class="micon mdi mdi-bullhorn"></span>
                            <span>{{{ trans('main.channels') }}}</span>
                        </a>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-6 tab-con">
                        <a href="javascript:void(0)" tab-id="t-medals" class="item-box sbox3">
                            <span class="micon mdi mdi-medal"></span>
                            <span>{{{ trans('main.badges') }}}</span>
                        </a>
                    </div>
                    <div class="h-10 visible-xs"></div>
                    <div class="col-md-1 col-sm-6 col-xs-6 tab-con">
                        <a href="javascript:void(0)" tab-id="t-record" class="item-box sbox3">
                            <span class="micon mdi mdi-video"></span>
                            <span>{{{ trans('main.future_courses') }}}</span>
                        </a>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-6 tab-con">
                    <a href="javascript:void(0)" tab-id="t-article" class="item-box sbox3">
                        <span class="micon mdi mdi-notebook"></span>
                        <span>{{{ trans('main.articles') }}}</span>
                    </a>
                </div>
                    <div class="h-10 visible-xs"></div>
                    <div class="col-md-1 col-sm-6 col-xs-12 tab-con">
                        <a href="javascript:void(0)" tab-id="t-request" class="item-box sbox3">
                            <span class="micon mdi mdi-camera-enhance"></span>
                            <span>{{{ trans('main.request_course') }}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="container-fluid bg-gray-s">

    <div class="container">

        <div class="col-xs-12 remove-padding-xs">

            <div id="t-biography" class="profile-section-fade profile-section sbox3 doview">
                <div class="row">
                    <div class="col-md-3 col-xs-12 col-sm-6 text-center">
                        <h4>{{{ trans('main.courses_feedback') }}}</h4>
                        <div class="h-5"></div>
                        <span class="dis-block">({{{ $video_rate or 0 }}})</span>
                        <div class="h-10"></div>
                        <div class="progress" dir="ltr">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="3.4"
                                 aria-valuemin="1" aria-valuemax="5" style="width:{{{ ($video_rate/5)*100 }}}%">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6 text-center">
                        <h4>{{{ trans('main.support_feedback') }}}</h4>
                        <div class="h-5"></div>
                        <span class="dis-block">({{{ $support_rate or 0 }}})</span>
                        <div class="h-10"></div>
                        <div class="progress" dir="ltr">
                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="{{{ $support_rate or 0 }}}"
                                 aria-valuemin="1" aria-valuemax="5" style="width:{{{ ($support_rate/5)*100 }}}%">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6 text-center">
                        <h4>{{{ trans('main.postal_feedback') }}}</h4>
                        <div class="h-5"></div>
                        <span class="dis-block">({{{ $sell_rate or 0 }}})</span>
                        <div class="h-10"></div>
                        <div class="progress" dir="ltr">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{{ $sell_rate or 0 }}}"
                                 aria-valuemin="1" aria-valuemax="5" style="width:{{{ ($sell_rate/5)*100 }}}%">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6 text-center">
                        <h4>{{{ trans('main.articles_feedback') }}}</h4>
                        <div class="h-5"></div>
                        <span class="dis-block">({{{ $article_rate or 0 }}})</span>
                        <div class="h-10"></div>
                        <div class="progress" dir="ltr">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{{ $article_rate or 0 }}}"
                                 aria-valuemin="1" aria-valuemax="5" style="width:{{{ ($article_rate/5)*100 }}}%">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-20"></div>
                @if(!isset($meta['biography']))
                    <div class="text-center">
                        <img src="/assets/images/empty/biography.png">
                        <div class="h-20"></div>
                        <span class="empty-first-line">{{{ trans('main.no_biography') }}}</span>
                        <div class="h-20"></div>
                    </div>
                @else
                    {{{ $meta['biography'] or '' }}}
                @endif
            </div>

            <div id="t-videos" class="profile-section-fade newest-container newest-container-p">
                <div class="body body-target-s">
                    <div class="row">
                        @foreach($videos as $vid)
                                <?php $meta = arrayToList($vid->metas,'option','value'); ?>
                                <div class="col-md-3 col-sm-6 col-xs-12 tab-con">
                                    <a href="/product/{{{ $vid->id or '' }}}" title="{{{ $vid->title or '' }}}" class="content-box">
                                        <img src="{{{ $meta['thumbnail'] or '' }}}"/>
										<h3>{!! str_limit($vid->title,35,'...') !!}</h3>
                                        <div class="footer">
                                            <label class="pull-right">{{{ $meta['duration'] or '0' }}} {{{ trans('main.min') }}}</label>
											<span class="boxicon mdi mdi-clock pull-right"></span>
											<span class="boxicon mdi mdi-wallet pull-left"></span>
                                            <label class="pull-left">{{{ currencySign() }}}{{{ $meta['price'] or '0' }}}</label>
                                        </div>
                                    </a>
                                </div>
                        @endforeach
                            @if(count($videos) == 0)
                                <div class="text-center">
                                    <img src="/assets/images/empty/Videos.png">
                                    <div class="h-20"></div>
                                    <span class="empty-first-line">{{{ trans('main.no_course_profile') }}}</span>
                                    <div class="h-20"></div>
                                </div>
                            @endif
                    </div>
                </div>
            </div>

            <div id="t-channels" class="profile-section-fade newest-container newest-container-p">
                <div class="body body-target-s">
                    <div class="row">
                        @foreach($channels as $ch)
                                <div class="col-md-3 col-sm-6 col-xs-12 tab-con">
                                    <a href="/chanel/{{{ $ch->username or '' }}}" title="{{{ $ch->title or '' }}}" class="content-box">
                                        <img src="{{{ $ch->avatar or '' }}}"/>
										<h3>{!! str_limit($ch->title,35,'...') !!}</h3>
                                    </a>
                                </div>
                        @endforeach
                            @if(count($channels) == 0)
                                <div class="text-center">
                                    <img src="/assets/images/empty/channel.png">
                                    <div class="h-20"></div>
                                    <span class="empty-first-line">{{{ trans('main.no_channel_profile') }}}</span>
                                    <div class="h-20"></div>
                                </div>
                            @endif
                    </div>
                </div>
            </div>

            <div id="t-medals" class="profile-section-fade newest-container newest-container-e">
                <div class="row">
                    @foreach($rates as $rate)
                        <div class="col-md-3 col-xs-12 tab-con">
                            <div class="product-card">
                                <h2>{{{ $rate['description'] or '' }}}</h2>
                                <h4>
                                    <?php $middle = explode(',',$rate['value']); ?>
                                    {{{ trans('main.From') }}}
                                    {{{ $middle[0] or 0 }}}
                                    {{{ trans('main.to') }}}
                                     {{{ $middle[1] or 0 }}}
                                    @if($rate['mode'] == 'videocount')
                                        {{{ 'Courses' }}}
                                    @elseif($rate['mode'] == 'day')
                                        {{{ 'Reg. Days' }}}
                                    @elseif($rate['mode'] == 'buycount')
                                        {{{ 'Purchases' }}}
                                    @elseif($rate['mode'] == 'sellcount')
                                        {{{ 'Sales' }}}
                                    @else
                                        {{{ 'Rates' }}}
                                    @endif
                                </h4>
                                <figure>
                                    <img src="{{{ $rate['image'] or '' }}}" alt="{{{ $rate->description or '' }}}" />
                                </figure>
                            </div>
                        </div>
                    @endforeach
                        @if(count($rates) == 0)
                            <div class="text-center">
                                <img src="/assets/images/empty/discount.png">
                                <div class="h-20"></div>
                                <span class="empty-first-line">{{{ trans('main.no_badge') }}}</span>
                                <div class="h-20"></div>
                            </div>
                        @endif
                </div>
        </div>

            <div id="t-article" class="profile-section-fade newest-container newest-container-p">
                <div class="body body-target-s body-target-s">
                    <div class="blog-section body-target-s">
                    @foreach($articles as $article)
                        		<div class="col-md-3 col-sm-6 col-xs-12 tab-con">
                                    <a href="/article/item/{{{ $article->id }}}" title="{{{ $article->title or '' }}}" class="content-box">
                                        <img src="{{{ $article->image or '' }}}"/>
										<h3>{!! str_limit($article->title,35,'...') !!}</h3>
                                    </a>
                                </div>
                    @endforeach
                    @if($articles->count() == 0)
                        <div class="text-center">
                            <img src="/assets/images/empty/articles.png">
                            <div class="h-20"></div>
                            <span class="empty-first-line">{{{ trans('main.no_articles_profile') }}}</span>
                            <div class="h-20"></div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>

            <div id="t-request" class="profile-section-fade newest-container newest-container-e">
                <div class="body body-target-s">
                    <div class="row">
                        <div class="col-md-6 col-xs-12 tab-con">
                            <div class="ucp-section-box white-s">
                                <div class="header back-orange">{{{ trans('main.request_course') }}}</div>
                                <div class="body body-target-s">
                                    <form method="post" action="/profile/request/store">
                                        <input type="hidden" name="user_id" value="{{{ $profile->id or 0 }}}">
                                        <div class="form-group">
                                            <label class="control-label" for="inputDefault">{{{ trans('main.title') }}}</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="inputDefault">{{{ trans('main.category') }}}</label>
                                            <select name="category_id" class="form-control font-s" required>
                                                @foreach($menus as $menu)
                                                    <optgroup label="{{{ $menu['title'] or '' }}}">
                                                        @foreach($menu['submenu'] as $sub)
                                                            <option value="{{{ $sub['id'] or '' }}}" >{{{ $sub['title'] or '' }}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="inputDefault">{{{ trans('main.description') }}}</label>
                                            <textarea name="description" rows="5" class="form-control" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-custom" value="save">{{{ trans('main.save_changes') }}}</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 tab-con">
                            <div class="ucp-section-box white-s">
                                <div class="header back-orange">{{{ trans('main.req_rules') }}}</div>
                                <div class="body body-target-s">
                                    {!! get_option('request_term') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="t-record" class="profile-section-fade newest-container newest-container-p">
                <div class="body bodt-target-s">
                    <div class="row">
                        @foreach($record as $vid)
                            <?php $meta = arrayToList($vid->metas,'option','value'); ?>
                            <div class="col-md-3 col-sm-6 col-xs-12 tab-con">
                                <a href="/product/{{{ $vid->id or '' }}}" title="{{{ $vid->title or '' }}}" class="content-box">
                                    <img src="{{{ $vid->image or '' }}}"/>
									<h3>{!! str_limit($vid->title,35,'...') !!}</h3>
                                    <div class="footer">
                                        <label class="pull-left">{{{ $vid->category->title or '' }}}</label>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                            @if(count($record) == 0)
                                <div class="text-center">
                                    <img src="/assets/images/empty/recording.png">
                                    <div class="h-20"></div>
                                    <span class="empty-first-line">{{{ trans('main.no_future_profile') }}}</span>
                                    <div class="h-20"></div>
                                </div>
                            @endif
                    </div>
                </div>
            </div>


        </div>

</div>
</div>


@section('script')
    <script>
    $(document).ready(function () {
        $('.ucp-menu-item a').click(function() {
            var id = $(this).attr('tab-id');
            $('.ucp-menu-item a').removeClass('arrow_box');
            $(this).addClass('arrow_box');
            $('.profile-section-fade').not('#'+id).fadeOut(500,function () {
                $('#'+id).fadeIn(500);
            });
        })
    })
    </script>
    <script>
        $('.raty').raty({ starType: 'i' });
    </script>
@endsection


@endsection
