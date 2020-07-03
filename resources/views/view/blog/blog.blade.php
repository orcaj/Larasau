@extends('view.layout.layout')
@section('title')
    {{{ $setting['site']['site_title'] or '' }}}
    {{{ trans('main.blog') }}} -
@endsection

@section('page')

    <div class="container-fluid">
        <div class="container">

            <div class="blog-section">
                @foreach($posts as $post)
                    <div class="row blog-post-box">
                        <div class="col-md-3 col-xs-12">
                            <img src="{{{ $post->image or '' }}}" class="img-responsive">
                        </div>
                        <div class="col-md-9 col-xs-12 text-section">
                            <a href="/blog/post/{{{ $post->id }}}"><h3>{{{ $post->title or '' }}}</h3></a>
                            {!!   $post->pre_content or '' !!}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="h-10"></div>
            <div class="pagi text-center center-block col-xs-12"></div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            pagination('.blog-section',{{{ $setting['site']['blog_post_count'] or 4 }}},0);
        $('.pagi').pagination({
            items: {!! count($posts) !!},
            itemsOnPage:{{{ $setting['site']['blog_post_count'] or 4 }}},
            cssStyle: 'light-theme',
            prevText: 'Pre.',
            nextText:'Next',
            onPageClick:function(pageNumber, event) {
            pagination('.blog-section',{{{ $setting['site']['blog_post_count'] or 4 }}},pageNumber-1);
        }
        });
        });
    </script>
@endsection
