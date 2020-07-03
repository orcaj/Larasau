@extends('user.layout.articlelayout')

@section('tab1','active')
@section('tab')
    <div class="h-20"></div>
    <div class="h-10"></div>
    <form method="post" action="/user/article/edit/store/{{{ $article->id or 0 }}}" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-md-1 tab-con">{{{ trans('main.title') }}}</label>
            <div class="col-md-6 tab-con">
                <input type="text" value="{{{ $article->title or '' }}}" class="form-control" name="title">
            </div>
            <label class="control-label col-md-1 tab-con">{{{ trans('main.category') }}}</label>
            <div class="col-md-4 tab-con">
                <select class="form-control font-s" name="cat_id">
                    @foreach(contentMenu() as $menu)
                        <optgroup label="{{{ $menu['title'] or '' }}}&nbsp;11{{{ count($menu['submenu']) }}}">
                            @if(count($menu['submenu']) == 0)
                                <option value="{{{ $menu['id'] or '' }}}" @if($menu['id'] == $article->cat_id) selected @endif>{{{ $menu['title'] or '' }}}</option>
                            @else
                                @foreach($menu['submenu'] as $sub)
                                    <option value="{{{ $sub['id'] or '' }}}" @if($sub['id'] == $article->cat_id) selected @endif>{{{ $sub['title'] or '' }}}</option>
                                @endforeach
                            @endif
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 tab-con">{{{ trans('main.article_summary') }}}</label>
            <div class="col-md-11 tab-con">
                <textarea class="ckeditor" name="pre_text">{{{ $article->pre_text or '' }}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 tab-con">{{{ trans('main.description') }}}</label>
            <div class="col-md-11 tab-con">
                <textarea class="ckeditor" name="text">{{{ $article->text or '' }}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 tab-con">{{{ trans('main.thumbnail') }}}</label>
            <div class="col-md-5 tab-con">
                <div class="input-group">
                    <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="image"><span class="formicon mdi mdi-eye"></span></span>
                    <input type="text" name="image" value="{{{ $article->image or '' }}}" dir="ltr" class="form-control">
                    <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                </div>
            </div>
            <label class="control-label col-md-1 tab-con">{{{ trans('main.status') }}}</label>
            <div class="col-md-3 tab-con">
                <select class="form-control font-s" name="mode">
                    <option value="draft" @if($article->mode == 'draft') selected @endif>{{{ trans('main.draft') }}}</option>
                    <option value="request" @if($article->mode == 'request') selected @endif>{{{ trans('main.send_for_review') }}}</option>
                    <option value="delete" @if($article->mode == 'delete') selected @endif>{{{ trans('main.unpublish_request') }}}</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" value="Save Article" class="btn btn-custom pull-left btn-100-p">
            </div>
        </div>
    </form>

@endsection
@section('script')
<script>$('#newarticle').text('{{{ $article->title or '' }}}')</script>
<script type="text/javascript" src="/assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.config.language = 'en';
    });
</script>
<script>$('#article-hover').css('background','#fff');</script>
<script>$('#article-hover span').css('color','#343871');</script>
@endsection
