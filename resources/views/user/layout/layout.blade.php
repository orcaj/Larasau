@include('view.layout.header',['title'=>'User Panel'])
@if($user['vendor'] == 1)
    @include('user.layout.menu')
@else
    @include('user.layout_user.menu')
@endif
@yield('pages')
@include('user.layout.modals')
@include('view.layout.footer')
