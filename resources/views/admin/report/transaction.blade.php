@extends('admin.newlayout.layout',['breadcom'=>['Reports','Transactions']])
@section('title')
{{{  trans('admin.transactions_bradcom') }}}
@endsection
@section('page')

    <section class="card">

        <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="date" id="fsdate" value="{!! $_GET['fsdate'] or '' !!}" class="text-center form-control" name="fsdate" placeholder="Start Date">
                                <input type="hidden" id="fdate" name="fdate">
                                <span class="input-group-append fdatebtn" id="fdatebtn">
                                    <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="date" id="lsdate" value="{!! $_GET['lsdate'] or '' !!}" class="text-center form-control" name="lsdate" placeholder="End Date">
                                <input type="hidden" id="ldate" name="ldate">
                                <span class="input-group-append ldatebtn" id="ldatebtn">
                                    <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="text-center btn btn-primary w-100" value="Show Results">
                        </div>
                    </div>
                </form>
            <div class="h-20"></div>
            @if(count($lists)>0)
                <div class="alert alert-info">
                    <span>{{{  trans('admin.since') }}}</span>
                    <span class="f-w-b">{{{ date('d F Y : H:i',$first->create_at) }}}</span>
                    <span>{{{  trans('admin.till') }}}</span>
                    <span class="f-w-b">{{{ date('d F Y : H:i',$last->create_at) }}}</span>
                    <span></span>{{{  trans('admin.total_transactions_amount') }}}</span>
                    <span class="f-w-b">{{{ $allPrice or 0 }}}</span>
                    <span>{{{  trans('admin.cur_dollar') }}}</span>
                    <span>{{{  trans('admin.and_business_income_is') }}}</span>
                    <span class="f-w-b">{{{ $siteIncome or 0 }}}</span>
                    <span>{{{  trans('admin.cur_dollar') }}}</span>
                </div>
            @endif
        </div>
    </section>

    <section class="card">
        <header class="card-heading">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
            </div>

            <h2 class="card-title"></h2>{{{  trans('admin.transactions_list_header') }}}</h2>
        </header>
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center">{{{  trans('admin.th_customer') }}}</th>
                    <th class="text-center">{{{  trans('admin.th_vendor') }}}</th>
                    <th class="text-center">{{{  trans('admin.th_course') }}}</th>
                    <th class="text-center">{{{  trans('admin.total_payment_table_header') }}}</th>
                    <th class="text-center">{{{  trans('admin.business_income_table_header') }}}</th>
                    <th class="text-center" width="150">{{{  trans('admin.th_date') }}}</th>
                    <th class="text-center" width="50">{{{  trans('admin.th_status') }}}</th>
                </tr>
                </thead>
                <tbody>
                    @if(!empty($lists))
                        @foreach($lists as $item)
                            <tr>
                                <th class="text-center"><a href="/profile/{{{ $item->buyer->id or 0 }}}" target="_blank">{{{ $item->buyer->name or '' }}}</a></th>
                                <th class="text-center"><a href="/profile/{{{ $item->user->id or 0 }}}" target="_blank">{{{ $item->user->name or '' }}}</a></th>
                                <th class="text-center"><a href="/product/{{{ $item->content->id or 0 }}}" target="_blank">{{{ $item->content->title or '' }}}</a></th>
                                <th class="text-center">{{{ $item->price or 0 }}}<br>{{{  trans('admin.cur_dollar') }}}</th>
                                <th class="text-center">{{{ $item->income or 0 }}}<br>{{{  trans('admin.cur_dollar') }}}</th>
                                <td class="text-center" width="150">{{{ date('d F Y : H:i',$item->create_at) }}}</td>
                                <td class="text-center">
                                    @if($item->mode == 'deliver')
                                        <i class="fa fa-check c-g" aria-hidden="true" title="Successfully Paid"></i>
                                    @else
                                        <i class="fa fa-times c-r" aria-hidden="true" title="Waiting For Payment"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </section>

@endsection
