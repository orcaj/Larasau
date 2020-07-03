@extends('admin.newlayout.layout',['breadcom'=>['Courses','Comments','Edit']])
@section('title')
    {{{ trans('admin.th_edit') }}} {{{ trans('admin.comment') }}}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form method="post" action="/admin/content/comment/store" class="form-horizontal form-bordered">
                <input type="hidden" name="id" value="{{{ $item->id or '' }}}">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="ckeditor" name="comment" required>{{{ $item->comment or '' }}}</textarea>
                    </div>
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


