@extends('user.layout.layout')

@section('pages')

    <div class="container-fluid">
        <div class="container">
            <div class="h-20"></div>
            <div class="col-md-6 col-xs-12 tab-con">
                <div class="ucp-section-box">
                    <div class="header back-red">{{{ trans('main.new_channel') }}}</div>
                    <div class="body">
                        <form method="post" action="/user/channel/store">

                            <div class="form-group">
                                <label class="control-label" for="inputDefault">{{{ trans('main.title') }}}</label>
                                <input type="text" name="title" class="form-control" id="inputDefault" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="inputDefault">{{{ trans('main.link') }}}</label>
                                <input type="text" name="username" class="form-control" id="inputDefault" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{{ trans('main.cover') }}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="image"><span class="formicon mdi mdi-eye"></span></span>
                                    <input type="text" name="image" dir="ltr" class="form-control">
                                    <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{{ trans('main.icon') }}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon view-selected img-icon-s" data-toggle="modal" data-target="#ImageModal" data-whatever="avatar" ><span class="formicon mdi mdi-eye"></span></span>
                                    <input type="text" name="avatar" dir="ltr" value="{{{ $edit->avatar or '' }}}" class="form-control">
                                    <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{{ trans('main.description') }}}</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-custom pull-left" value="Save Changes">{{{ trans('main.save_changes') }}}</button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 tab-con">
                @if(count($channels) == 0)
                    <div class="text-center">
                        <img src="/assets/images/empty/channel.png">
                        <div class="h-20"></div>
                        <span class="empty-first-line">{{{ trans('main.no_channel') }}}</span>
                        <div class="h-10"></div>
                        <span class="empty-second-line">
                        <span>{{{ trans('main.channel_desc') }}}</span>
                        </span>
                    </div>
                @else
                    <div class="table-responsive">
                            <table class="table ucp-table" id="chanel-table">
                            <thead class="back-blue">
                            <th class="text-center">{{{ trans('main.title') }}}</th>
                            <th class="text-center">{{{ trans('main.link') }}}</th>
                            <th class="text-center">{{{ trans('main.views') }}}</th>
                            <th class="text-center">{{{ trans('main.contents') }}}</th>
                            <th class="text-center">{{{ trans('main.status') }}}</th>
                            <th class="text-center">{{{ trans('main.controls') }}}</th>
                            </thead>
                            <tbody>
                            @foreach($channels as $channel)
                                <tr>
                                    <td class="text-center">{{{ $channel->title or '' }}}</td>
                                    <td class="text-center"><a href="/chanel/{{{ $channel->username or '' }}}">{{{ $channel->username or '' }}}</a></td>
                                    <td class="text-center">{{{ $channel->view or '' }}}</td>
                                    <td class="text-center">{{{ $channel->contents_count or '' }}}</td>
                                    <td class="text-center">
                                    @if($channel->mode==null Or $channel->mode=='pending')
                                        <b class="blue-s">{{{ trans('main.waiting') }}}</b>
                                    @else
                                        <b class="green-s">{{{ trans('main.active') }}}</b>
                                    @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/user/channel/request/{{{ $channel->id or '' }}}" title="Request channel verification"><span class="crticon mdi mdi-check-decagram"></span></a>
                                        <a href="/user/channel/video/{{{ $channel->id or '' }}}" title="Add video to channel"><span class="crticon mdi mdi-file-video"></span></a>
                                        <a href="#" data-href="/user/channel/delete/{{{ $channel->id or '' }}}" data-toggle="modal" data-target="#confirm-delete" title="Delete channel"><span class="crticon mdi mdi-delete-forever"></span></a>
                                        <a href="/user/channel/edit/{{{ $channel->id or '' }}}"><span class="crticon mdi mdi-lead-pencil"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                @endif
            </div>

        </div>
    </div>


@endsection

@section('script')
    <script>$('#channel-hover').addClass('item-box-active');</script>
@endsection
