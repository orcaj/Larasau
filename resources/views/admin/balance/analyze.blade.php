@extends('admin.newlayout.layout',['breadcom'=>['Accounting','Financial Analyser']])
@section('title')
    {{{ trans('admin.financial_analyser') }}}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="date" id="fsdate" value="{!! $_GET['fsdate'] or '' !!}" class="text-center form-control" name="fsdate" placeholder="Start Date">
                            <input type="hidden" id="fdate" name="fdate" value="{!! $_GET['fdate'] or '' !!}">
                            <span class="input-group-append fdatebtn" id="fdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="date" id="lsdate" value="{!! $_GET['lsdate'] or '' !!}" class="text-center form-control" name="lsdate" placeholder="End Date">
                            <input type="hidden" id="ldate" name="ldate" value="{!! $_GET['ldate'] or '' !!}">
                            <span class="input-group-append ldatebtn" id="ldatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="submit" class="text-center btn btn-primary w-100" value="Show Results">
                        </div>
                    </div>
                </div>
            </form>
            <div class="h-20"></div>
            @if(count($lists)>0)
                <div class="alert alert-info">
                    <span>{{{ trans('admin.from') }}}</span>
                    <span class="f-w-b">{{{ date('d F Y / H:i',$last->create_at) }}}</span>
                    <span>{{{ trans('admin.till') }}}</span>
                    <span class="f-w-b">{{{ date('d F Y / H:i',$first->create_at) }}}</span>
                    <span>{{{ trans('admin.your_business_income_is') }}}</span>
                    <span class="f-w-b" dir="ltr">{{{ $alladd or 0 }}}</span>
                    <span>{{{ trans('admin.cur_dollar') }}} {{{ trans('admin.and_total_cost_is') }}}</span>
                    <span class="f-w-b color-red-i" dir="ltr">{{{ $allminus or 0 }}}</span>
                    <span>{{{ trans('admin.cur_dollar') }}}.</span>
                    <span>{{{ trans('admin.business_net_profit') }}}</span>
                    <b class="color-blue" dir="ltr">{{{ $alladd-$allminus }}}</b>
                    <span>{{{ trans('admin.cur_dollar') }}}</span>
                </div>
            @endif
        </div>
    </section>
    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center" width="50">#</th>
                    <th class="text-center" width="170">{{{ trans('admin.th_date') }}}</th>
                    <th class="text-center">{{{ trans('admin.th_title') }}}</th>
                    <th class="text-center">{{{ trans('admin.document_type') }}}</th>
                    <th class="text-center">{{{ trans('admin.amount') }}}</th>
                    <th class="text-center">{{{ trans('admin.creator') }}}</th>
                    <th class="text-center">{{{ trans('admin.description') }}}</th>
                    <td class="text-center">{{{ trans('admin.th_controls') }}}</td>
                </tr>
                </thead>
                <tbody>
                    @foreach($lists as $item)
                        <tr>
                            <td class="text-center">{{{ $item->id }}}</td>
                            <td class="text-center" width="170">{{{ date('d F Y : H:i',$item->create_at) }}}</td>
                            <td class="text-center">{{{ $item->title }}}</td>
                            <td class="text-center">
                                @if($item->type == 'add')
                                    <span class="f-w-b color-green">{{{ trans('admin.addiction') }}}</span>
                                @else
                                    <span class="f-w-b color-red-i">{{{ trans('admin.deduction') }}}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->type == 'add')
                                    <span class="f-w-b color-green">{{{ $item->price or 0 }}}+</span>
                                @else
                                    <span class="color-red-i f-w-b">{{{ $item->price or 0 }}}-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->mode == 'auto')
                                    <span>{{{ trans('admin.automatic') }}}</span>
                                @elseif($item->mode == 'user')
                                    <span><a href="/admin/user/item/{{{ $item->exporter->id or 0 }}}">{{{ $item->exporter->name or '' }}}</a></span>
                                @endif
                            </td>
                            <td class="text-center">{{{ $item->description or '' }}}</td>
                            <td class="text-center">
                                    <a href="/admin/balance/print/{{{ $item->id }}}" target="_blank" title="Print Document" ><i class="fa fa-print" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
