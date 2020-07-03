@extends('admin.newlayout.layout',['breadcom'=>[trans('admin.send_email')]])
@section('title')
    {!! trans('admin.send_email') !!}
@endsection
@section('page')

    @if(!empty(session('status')))
    @if(session('status') == 'error')
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>{{{ trans('admin.email_unable') }}}</strong>
        </div>
    @else
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>
                {{{ trans('admin.email_sent_successfully') }}}
            </strong>
        </div>
    @endif
    @endif

    <section class="card">
        <div class="card-body">

            <form action="/admin/email/sendMail" class="form-horizontal form-bordered" method="post">

                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.th_title') }}}</label>
                    <div class="col-md-11">
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.receipts') }}}</label>
                    <div class="col-md-11" dir="ltr">
                            <select name="recipent[]" multiple  class="form-control selectric text-left">
                                @foreach($users as $user)
                                    <option value="{{{ $user->email }}}">{{{ $user->username }}} ({{{ $user->name }}})</option>
                                @endforeach
                            </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-1 control-label" for="inputDefault">{{{ trans('admin.templates') }}}</label>
                    <div class="col-md-11">
                        <select id="template" name="template" class="form-control">
                            <option value=""></option>
                            @foreach($template as $temp)
                                <option value="{{{ $temp->id }}}">{{{ $temp->title }}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">{{{ trans('admin.attachments') }}}</label>
                    <div class="col-md-11">
                        <div class="input-group">
                            <input type="text" name="attach" dir="ltr" class="form-control">
                            <span class="input-group-append click-for-upload cu-p">
                                <span class="input-group-text"><i class="fa fa-upload" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="summernote" name="message" required></textarea>
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



