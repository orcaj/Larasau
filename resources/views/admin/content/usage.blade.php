@extends('admin.newlayout.layout',['breadcom'=>['Courses','Usage List']])
@section('title')
   {{{ trans('admin.usage_list') }}}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-none" id="datatable-details">
                    <thead>
                    <tr>
                        <th class="text-center" width="30">#</th>
                        <th >{{{ trans('admin.th_username') }}}</th>
                        <th class="text-center" width="150">{{{ trans('admin.spend_time') }}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $index=>$item)
                        <tr>
                            <td class="text-center">{!! ($index + 1) !!}</td>
                            <td>{!! $item->user->username or '' !!}</td>
                            <td class="text-center" width="150">{!! $item->total * 5 !!} Min</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
