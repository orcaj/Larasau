@extends('view.layout.layout')
@section('title')
    {{{ get_option('site_title','') }}} - {{{ trans('main.soon') }}}

@endsection
@section('page')

    <div class="container-fluid">
        <div class="row cat-tag-section">
            <div class="container">
                <div class="col-md-5 col-xs-12 tab-con">
                    <a href="/user/video/record" class="btn btn-custom pull-left"><span>{{{ trans('main.new_soon') }}}</span></a>
                    <div class="btn-group soon-btngp ptopz" data-toggle="buttons">
                        <label class="btn btn-primary @if(!isset($_GET['mode']) || $_GET['mode']=='all') active @endif">
                            <input type="radio" name="mode" value="all" @if(isset($_GET['mode']) && $_GET['mode']=='all') checked @endif> {{{ trans('main.all') }}}
                        </label>
                        <label class="btn btn-primary @if(isset($_GET['mode']) && $_GET['mode']=='publish') active @endif">
                            <input type="radio" name="mode" value="publish" @if(isset($_GET['mode']) && $_GET['mode']=='publish') checked @endif>{{{ trans('main.soon') }}}
                        </label>
                        <label class="btn btn-primary @if(isset($_GET['mode']) && $_GET['mode']=='accept') active @endif">
                            <input type="radio" name="mode" value="accept" @if(isset($_GET['mode']) && $_GET['mode']=='accept') checked @endif>{{{ trans('main.published') }}}
                        </label>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12 text-left tab-con"></div>
                <div class="col-md-3 col-xs-12 text-left tab-con">
                    <div class="box marz">
                        <div class="container-2">
                            <form>
                                <input type="search" id="search" name="q" value="{{{ $_GET['q'] or '' }}}" placeholder="Search in coming soon courses" />
                                <span class="icon"><i class="homeicon mdi mdi-magnify"></i></span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="h-20"></div>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="col-md-3 col-xs-12 tab-con">
                <div class="ucp-section-box sbox3">
                    <div class="header back-orange text-center header-f"><span>{{{ trans('main.category') }}}</span></div>
                    <div class="body">
                    <ul id="accordion" class="cat-filters-li accordion">
                        @foreach($setting['category'] as $mainCategory)
                            <li>
                                @if(count($mainCategory->childs)>0)
                                    <div class="link">{{{$mainCategory->title or ''}}}<i class="mdi mdi-chevron-down"></i></div>
                                        <ul class="submenu">
                                            @foreach($mainCategory->childs as $child)
                                                <li><input name="category" type="checkbox" id="cat{{{ $child->id or '' }}}" value="{{{ $child->id or '' }}}" class="category-item" @if(isset($_GET['cat']) && in_array($child->id,$_GET['cat'])) checked @endif><label for="cat{{{ $child->id or '' }}}"><span></span>{{{ $child->title or '' }}}</label></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <div class="link">{{{$mainCategory->title or ''}}}<i class="mdi mdi-chevron-down"></i></div>
                                    <ul class="submenu">
                                        <li><input name="category" type="checkbox" id="cat{{{ $mainCategory->id or '' }}}" value="{{{ $mainCategory->id or '' }}}" class="category-item" @if(isset($_GET['cat']) && in_array($mainCategory->id,$_GET['cat'])) checked @endif><label for="cat{{{ $mainCategory->id or '' }}}"><span></span>{{{ $mainCategory->title or '' }}}</label></li>
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-xs-12">
                <div class="newest-container newest-container-b">
                    <div class="row body body-target body-target-s">
                        @if(!empty($list))
                            @foreach($list as $content)
                                <div class="col-md-4 col-sm-6 col-xs-12 pagi-content tab-con">
                                    @if($content->content_id != null)
                                        <a href="/product/{{{ $content->content_id or 'javascript:void(0);' }}}" title="{{{ $content->title or '' }}}" class="content-box pagi-content-box">
                                    @else
                                        <a href="javascript:void(0);" title="{{{ $content->title or '' }}}" class="content-box pagi-content-box">
                                    @endif
                                        <div class="img-container text-center center-block">
                                            <img src="{{{ $content->image or '' }}}"/>
                                        </div>
										<h3>{!! str_limit($content->title,30,'...') !!}</h3>
                                        <div class="footer">
                                            @if($content->content_id == null)
                                                <span class="pull-right mod-r" data-toggle="modal" data-target="#dModal{{{ $content->id or 0 }}}"><b>{{{ trans('main.description') }}}</b></span>
                                            @else
                                                <span class="pull-right mod-r"><b class="green-s">{{{ trans('main.published') }}}</b></span>
                                            @endif
                                            @if($content->mode == 'publish')
                                                @if(count($content->fans)==1)
													<span class="boxicon mdi mdi-heart pull-left"></span>
                                                    <span class="pull-left request-unfollow-icon" title="Unfollow" onclick="window.location.href ='/record/unfollow/{{{ $content->id or 0 }}}'">{{{ $content->fans_count or 0 }}}{{{ trans('main.followers') }}}</span>
                                                @else
													<span class="boxicon mdi mdi-heart-outline pull-left"></span>
                                                    <span class="pull-left request-follow-icon" title="Follow" onclick="window.location.href ='/record/follow/{{{ $content->id or 0 }}}'">{{{ $content->fans_count or 0 }}}{{{ trans('main.followers') }}}</span>
                                                @endif
                                            @elseif($content->content_id != null)
												<span class="boxicon mdi mdi-check-bold pull-left"></span>
                                                <span class="pull-left request-accept-icon">{{{ trans('main.published') }}}</span>
                                            @endif
                                        </div>
                                    </a>
                                    <div id="dModal{{{ $content->id or 0 }}}" class="modal fade"  role="dialog">
                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">{{{ trans('main.extra_info') }}}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>{{{ $content->description or 'Description not found.' }}}</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-custom pull-left" data-dismiss="modal">{{{ trans('main.close') }}}</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="h-10"></div>
                    <div class="pagi text-center center-block col-xs-12"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script>
        $(function() {
            pagination('.body-target',@if(isset($setting['site']['category_content_count'])) {{{ $setting['site']['category_content_count'] or 6 }}} @endif,0);
            $('.pagi').pagination({
                items: {!! count($list) !!},
                itemsOnPage: @if(isset($setting['site']['category_content_count'])) {{{ $setting['site']['category_content_count'] or 6 }}} @endif,
                cssStyle: 'light-theme',
                prevText: 'Pre.',
        		nextText:'Next',
                onPageClick:function(pageNumber, event) {
                    pagination('.body-target',@if(isset($setting['site']['category_content_count'])) {{{ $setting['site']['category_content_count'] or 6 }}} @endif,pageNumber-1);
                }
            });
        });
    </script>
    <script type="application/javascript" src="/assets/javascripts/category-page-custom.js"></script>
@endsection