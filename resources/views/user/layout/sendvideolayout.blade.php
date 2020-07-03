@include('view.layout.header',['title'=>'User Panel'])
@section('title','User Panel')
<div class="container-fluid no-padding-xs" style="background: url({{{ get_option('upload_page_background','/assets/images/plant.jpg') }}});background-size: cover;">
    <div class="h-20"></div>
    <div class="container no-padding-xs">
        <div class="col-md-12 col-xs-12">
            @yield('pages')
        </div>
    </div>
    <div class="h-20"></div>
    <div class="h-30"></div>
</div>
@include('user.layout.modals')
@include('view.layout.footer')
