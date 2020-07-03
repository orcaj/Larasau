@extends('view.layout.layout')
@section('title')
    {{{ $setting['site']['site_title'] or '' }}}
@endsection
@section('page')

    @include('view.parts.slider')
    @include('view.parts.container')
    @if(isset($setting['site']['main_page_newest_container']) && $setting['site']['main_page_newest_container'] == 1)
        @include('view.parts.newest')
    @endif
    @if(isset($setting['site']['main_page_popular_container']) && $setting['site']['main_page_popular_container'] == 1)
        @include('view.parts.popular')
        @include('view.parts.most_sell')
    @endif
    @if(isset($setting['site']['main_page_vip_container']) && $setting['site']['main_page_vip_container'] == 1)
        @include('view.parts.vip')
    @endif
    @include('view.parts.news')

@endsection