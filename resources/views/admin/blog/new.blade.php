@extends('admin.newlayout.layout',['breadcom'=>[trans('admin.blog_posts'),trans('admin.new_post')]])
@section('title')
    {{{ trans('admin.new_post') }}}
@endsection
@section('page')
    <section class="card">
        <div class="card-body">

            <form action="/admin/blog/post/store" class="form-horizontal form-bordered" method="post">

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                    <div class="col-md-10">
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.category') }}}</label>
                    <div class="col-md-10">
                        <select id="category_id" class="form-control">
                            <option value=""></option>
                            @foreach($category as $cat)
                                <option value="{{{ $cat->id }}}">{{{ $cat->title }}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.thumbnail') }}}</label>
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true"></i></span>
                            </span>
                            <input type="text" name="image" dir="ltr" class="form-control">
                            <span class="input-group-append click-for-upload cu-p"><span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span></span>
                        </div>
                    </div>
                </div>

                <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.short_description') }}}</label>
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="pre_content" required></textarea>
                    </div>
                </div>

                <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.description') }}}</label>
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="content" required></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{{ trans('admin.tags') }}}</label>
                    <div class="col-md-10">
                        <input type="text" name="tags" class="form-control inputtags">
                    </div>
                </div>

                <div class="col-12">
                    <div class="custom-switches-stacked">
                        <label class="custom-switch">
                            <input type="hidden" name="comment" value="disable">
                            <input type="checkbox" name="comment" value="enable" checked class="custom-switch-input"/>
                            <span class="custom-switch-indicator"></span>
                            <label class="custom-switch-description" for="inputDefault">{{{ trans('admin.comments_enabled') }}}</label>
                        </label>
                        <label class="custom-switch">
                            <input type="hidden" name="mode" value="draft">
                            <input type="checkbox" name="mode" value="publish" checked class="custom-switch-input"/>
                            <span class="custom-switch-indicator"></span>
                            <label class="custom-switch-description" for="inputDefault">{{{ trans('admin.publish') }}}</label>
                        </label>
                    </div>
                    <div class="h-15"></div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{{ trans('admin.save_changes') }}}</button>
                    </div>
                </div>

            </form>
        </div>
    </section>
@endsection
@section('script')
    <script>$(".inputtags").tagsinput('items');</script>
@endsection

