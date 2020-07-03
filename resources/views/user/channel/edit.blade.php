@extends('user.layout.layout')

@section('pages')

    <div class="container-fluid">
        <div class="container">
            <div class="h-20"></div>
            <div class="col-md-6 col-xs-12 tab-con">
                <div class="ucp-section-box">
                    <div class="header back-red">{{{ trans('main.edit_channel') }}}</div>
                    <div class="body">
                        <form method="post" action="/user/channel/edit/store/{{{ $edit->id or '' }}}">

                            <div class="form-group">
                                <label class="control-label" for="inputDefault">{{{ trans('main.title') }}}</label>
                                <input type="text" name="title" value="{{{ $edit->title or '' }}}" class="form-control" id="inputDefault" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="inputDefault">{{{ trans('main.link') }}}</label>
                                <input type="text" value="{{{ $edit->username or '' }}}" class="form-control" id="inputDefault" disabled="disabled">
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{{ trans('main.cover') }}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="image"><span class="formicon mdi mdi-eye"></span></span>
                                        <input type="text" name="image" dir="ltr" value="{{{ $edit->image or '' }}}" class="form-control">
                                        <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                    </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{{ trans('main.icon') }}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="avatar"><span class="formicon mdi mdi-eye"></span></span>
                                    <input type="text" name="avatar" dir="ltr" value="{{{ $edit->avatar or '' }}}" class="form-control">
                                    <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{{ trans('main.description') }}}</label>
                                <textarea class="form-control" name="description">{!!  $edit->description or '' !!}</textarea>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-custom pull-left">{{{ trans('main.save_changes') }}}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 tab-con">
                        <div class="table-responsive">
                            <table class="table ucp-table">
                            <thead class="back-blue">
                            <th class="text-center">{{{ trans('main.title') }}}</th>
                            <th class="text-center">{{{ trans('main.link') }}}</th>
                            <th class="text-center">{{{ trans('main.views') }}}</th>
                            <th class="text-center">{{{ trans('main.status') }}}</th>
                            <th class="text-center">{{{ trans('main.controls') }}}</th>
                            </thead>
                            <tbody>
                            @foreach($channels as $channel)
                                <tr>
                                    <td class="text-center">{{{ $channel->title or '' }}}</td>
                                    <td class="text-center"><a href="/chanel/{{{ $channel->username or '' }}}">{{{ $channel->username or '' }}}</a></td>
                                    <td class="text-center">{{{ $channel->view or '' }}}</td>
                                    <td class="text-center">
                                        @if($channel->mode==null || $channel->mode=='pending' || $channel->mode=='draft')
                                            <b class="orange-s">{{{ trans('main.waiting') }}}</b>
                                        @else
                                            <b class="green-s">{{{ trans('main.active') }}}</b>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/user/channel/video/{{{ $channel->id or '' }}}" title="Add video to channel"><span class="crticon mdi mdi-file-video"></span></a>
                                        <a href="#" data-href="/user/channel/delete/{{{ $channel->id or '' }}}" data-toggle="modal" data-target="#confirm-delete"><span class="crticon mdi mdi-delete-forever"></span></a>
                                        <a href="/user/channel/edit/{{{ $channel->id or '' }}}"><span class="crticon mdi mdi-lead-pencil"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
        </div>
    </div>


@endsection

@section('script')
    <script>$('#channel-hover').addClass('item-box-active');</script>
@endsection
