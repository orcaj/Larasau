@extends('view.layout.layout')
@section('title')
    {{{ get_option('site_title','') }}} Search - {{{ $_GET['q'] or '' }}}
@endsection
@section('page')

    <div class="container-fluid">
        <div class="row cat-search-section">
            <div class="container">
                <div class="col-md-6 col-sm-6 col-xs-12 cat-icon-container">
                    <span> {{{ $search_title or 'Search' }}} "{{{ $_GET['q'] or '' }}}"</span>
                </div>
                <div class="col-md-3">
                    <div class="h-10"></div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                        <form>
                            <select class="form-control font-s" name="search_type">
                                <option value="content_title" @if(isset($_GET['type']) && $_GET['type']=='content_title') selected @endif>{{{ trans('main.course_title') }}}</option>
                                <option value="content_code" @if(isset($_GET['type']) && $_GET['type']=='content_code') selected @endif>{{{ trans('main.item_no') }}}</option>
                                <option value="content_filter" @if(isset($_GET['type']) && $_GET['type']=='content_filter') selected @endif>{{{ trans('main.filters') }}}</option>
                                <option value="user_name" @if(isset($_GET['type']) && $_GET['type']=='user_name') selected @endif>{{{ trans('main.username') }}}</option>
                            </select>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <div class="h-10"></div>
    <div class="h-20"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="col-md-12 col-xs-12">
                    @if(!isset($_GET['type']) || (isset($_GET['type']) && $_GET['type']!='user_name'))
                        <div class="newest-container newest-container-b">
                        <div class="row body body-target body-target-s">
                            @if($contents)
                                @foreach($contents as $content)
                                    <div class="col-md-3 col-sm-6 col-xs-12 pagi-content tab-con">
                                    <a href="/product/{{{ $content['id'] or '' }}}" title="{{{ $content['title'] or '' }}}" class="content-box">
                                        <img src="{{{ $content['metas']['thumbnail'] or '' }}}"/>
										<h3>{!! str_limit($content['title'],30,'...') !!}</h3>
                                        <div class="footer">
                                            <label class="pull-right">{{{ $content['metas']['duration'] or '' }}} {{{ trans('main.min') }}}</label>
											<span class="boxicon mdi mdi-clock pull-right"></span>
											<span class="boxicon mdi mdi-wallet pull-left"></span>
                                            @if(isset($content['metas']['price']) && $content['metas']['price']>0)
                                                <label class="pull-left">{{{ currencySign() }}}{{{ $content['metas']['price'] or '' }}}</label>
                                            @else
                                                <label class="pull-left">{{{ trans('main.free_item') }}}</label>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            @else
                                <h3>{{{ trans('main.no_search_result') }}}</h3>
                            @endif
                        </div>
                        <div class="h-10"></div>
                        @if($contents)
                            <div class="pagi text-center center-block col-xs-12"></div>
                         @endif
                    </div>
                    @else
                        <div class="newest-container newest-container-b">
                            <div class="row body body-target body-target-s">
                                @if($contents)
                                    @foreach($contents as $content)
                                        <div class="col-md-2 col-sm-3 col-xs-6 pagi-content">
                                            <a href="/prfile/{{{ $content['id'] or '' }}}" title="{{{ $content['name'] or '' }}}" class="user-box pagi-content-box">
                                                <img src="{{{ $content['metas']['avatar'] or '' }}}"/>
                                                <h3>{!! $content['name'] or '' !!}</h3>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <h3>{{{ trans('main.no_search_result') }}}</h3>
                                @endif
                            </div>
                            <div class="h-10"></div>
                            @if($contents)
                                <div class="pagi text-center center-block col-xs-12"></div>
                            @endif
                        </div>
                    @endif
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
                items: {!! count($contents) !!},
                itemsOnPage: @if(isset($setting['site']['category_content_count'])) {{{ $setting['site']['category_content_count'] or 6 }}} @endif,
            cssStyle: 'light-theme',
            prevText: '<i class="fa fa-angle-left"></i>',
            nextText:'<i class="fa fa-angle-right"></i>',
            onPageClick:function(pageNumber, event) {
            pagination('.body-target',@if(isset($setting['site']['category_content_count'])) {{{ $setting['site']['category_content_count'] or 6 }}} @endif,pageNumber-1);
        }
        });
        });
    </script>
    <script type="application/javascript" src="/assets/javascripts/category-page-custom.js"></script>
@endsection
