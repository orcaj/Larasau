@extends('view.layout.layout')
@section('title')
    {{{ $setting['site']['site_title'] or '' }}}
@endsection
@section('page')
    <div class="container-fluid">
        <div class="h-20"></div>
        <div class="container">
            <div class="col-xs-12">
                {!! $content !!}
            </div>
        </div>
        <div class="h-20"></div>
    </div>
@endsection
