@extends('user.layout.layout')

@section('pages')

    @if(!empty(session('Message')))
        {!! session('Message') !!}
    @endif

    <div class="h-20"></div>
    <form method="post" action="/user/channel/store">

        <div class="form-group">
            <label class="col-md-1 control-label" for="inputDefault">{{{ trans('main.title') }}}</label>
            <div class="col-md-11">
                <input type="text" name="title" class="form-control" id="inputDefault" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-1 control-label" for="inputDefault">{{{ trans('main.link') }}}</label>
            <div class="col-md-11">
                <input type="text" name="username" class="form-control" id="inputDefault" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-1 control-label">{{{ trans('main.cover') }}}</label>
            <div class="col-md-11">
                <div class="input-group">
                    <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="image"><i class="fa fa-eye" aria-hidden="true"></i></span>
                    <input type="text" name="image" dir="ltr" class="form-control">
                    <span class="input-group-addon click-for-upload img-icon-s"><i class="fa fa-upload" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-1 control-label">{{{ trans('main.icon') }}}</label>
            <div class="col-md-11">
                <div class="input-group">
                    <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="avatar"><i class="fa fa-eye" aria-hidden="true"></i></span>
                    <input type="text" name="avatar" dir="ltr" class="form-control">
                    <span class="input-group-addon click-for-upload img-icon-s"><i class="fa fa-upload" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-1 control-label">{{{ trans('main.description') }}}</label>
            <div class="col-md-11">
                <textarea class="form-control" name="description"></textarea>
            </div>
        </div>


        <div class="form-group">
            <button type="submit" class="btn btn-custom pull-left" value="Save Changes">{{{ trans('main.save_changes') }}}</button>
        </div>


    </form>


@endsection

@section('script')
    <script>$('#channel-hover').addClass('item-box-active');</script>
@endsection
