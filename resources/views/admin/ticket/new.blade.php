@extends('admin.newlayout.layout',['breadcom'=>['Support','New Ticket']])
@section('title')
    {{{ trans('admin.submit_ticket') }}}
@endsection
@section('page')


    <section class="card">
        <div class="card-body">
            <form action="/admin/ticket/store" class="form-horizontal form-bordered" method="post">
                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                    <div class="col-md-11">
                        <input type="text" name="title" value="{{{ $_GET['title'] or '' }}}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group" id="userone">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.department') }}}</label>
                    <div class="col-md-11">
                        <select name="category_id" class="form-control select2">
                            @foreach($category as $cat)
                                <option value="{{{ $cat->id or 0 }}}">{{{ $cat->title or '' }}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group" id="userone">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.users') }}}</label>
                    <div class="col-md-11">
                        <select name="user_id" class="form-control select2">
                            @foreach($users as $user)
                                <option value="{{{ $user->id }}}" @if(isset($_GET['uid']) && $_GET['uid'] == $user->id) selected @endif>{{{ $user->username }}} ({{{ $user->name }}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="msg" required></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" type="submit">{{{ trans('admin.send') }}}</button>
                    </div>
                </div>

            </form>
        </div>
    </section>
@endsection


