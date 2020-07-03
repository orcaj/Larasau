@extends('user.layout.layout')

@section('pages')

    <div class="container-fluid">
        <div class="container">
            <div class="h-20"></div>
            <div class="col-md-6 col-xs-12 tab-con">
                <div class="ucp-section-box">
                    <div class="header back-red">{{{ trans('main.channel_ver_req') }}}</div>
                    <div class="body">
                        <form method="post" action="/user/channel/request/store">

                            <div class="form-group">
                                <label class="control-label">{{{ trans('main.select_channel') }}}</label>
                                <select name="channel_id" class="form-control font-s">
                                    @foreach($channels as $channel)
                                        <option value="{{{ $channel->id or 0 }}}" @if(isset($id) && $channel->id == $id) selected @endif>{{{ $channel->title or '' }}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{{ trans('main.verification_docs') }}}</label>
                                <div class="input-group">
                                    <input type="text" name="attach" dir="ltr" class="form-control">
                                    <span class="input-group-addon click-for-upload img-icon-s"><span class="formicon mdi mdi-arrow-up-thick"></span></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="inputDefault">{{{ trans('main.description') }}}</label>
                                <input type="text" name="title" class="form-control" id="inputDefault" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-custom pull-left" value="send">{{{ trans('main.send') }}}</button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 tab-con">
                <div class="table-responsive">
                            <table class="table ucp-table" id="request-table">
                            <thead class="back-blue">
                            <th class="text-right">{{{ trans('main.description') }}}</th>
                            <th class="text-center" width="150">{{{ trans('main.channel') }}}</th>
                            <th class="text-center">{{{ trans('main.documents') }}}</th>
                            <th class="text-center">{{{ trans('main.status') }}}</th>
                            </thead>
                            <tbody>
                            @foreach($requests as $channel)
                                <tr>
                                    <td class="text-right text-justify">{{{ $channel->title or '' }}}</td>
                                    <td class="text-center" width="150">{{{ $channel->channel->title or '' }}}</td>
                                    <td class="text-center"><a href="{{{ $channel->attach or '#' }}}" target="_blank">{{{ trans('main.view') }}}</a></td>
                                    <td class="text-center">
                                    @if($channel->mode==null Or $channel->mode=='pending' || $channel->mode=='draft')
                                        <b class="orange-s">{{{ trans('main.waiting') }}}</b>
                                    @else
                                        <b class="green-s">{{{ trans('main.active') }}}</b>
                                    @endif
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
