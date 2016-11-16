@extends('main')

@section('content')

    <h1>
        Settlement Report
        <div class="btn-toolbar pull-right">
            <a href="{{ Request::url() }}/?download=csv&amp;source=aggregate_settlement_report&amp;filename={{ $export_view_filename }}" class="btn btn-default"><span class="glyphicon glyphicon-save"></span> Download Report</a>
            <a href="{{ Request::url() }}/?download=csv&amp;filename={{ $export_api_filename }}" class="btn btn-default"><span class="glyphicon glyphicon-save"></span> Download Raw</a>
        </div>
    </h1>
    @include('includes.page.breadcrumb', ['over' => [1 => isset($installation->installation->merchant->name) ? $installation->installation->merchant->name : Request::segment(2)], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])
    <h3><span>Amount: {{ '&pound;' . number_format($settlement_report['amount']/100, 2) }}</span></h3>
    <h5>
        <span>Date: {{ date('d/m/Y', strtotime($settlement_report['settlement_date'])) }}</span> |
        <span>Lender: {{ ucwords($settlement_report['provider']) }}</span>
    </h5>

    <div class="panel panel-default">

        <div class="panel-heading"><h4>Settlements</h4></div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                {{-- TABLE HEADER WITH FILTERS --}}
                {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
                <tr>
                    {{--TITLES--}}
                    <th>Order Date</th>
                    <th>Notification Date</th>
                    <th>Customer</th>
                    <th>Post Code</th>
                    <th>Application ID</th>
                    <th>Retailer Reference</th>
                    <th>Order Amount</th>
                    <th>Type</th>
                    <th>Deposit</th>
                    <th>Loan Amount</th>
                    <th>Subsidy</th>
                    <th>Adjustment</th>
                    <th>Settlement Amount</th>
                </tr>
                {!! Form::close() !!}
                <tr>
                @foreach($aggregate_settlement_report as $item)
                    <tr>
                        <td>{{ $item['order_date'] }}</td>
                        <td>{{ $item['notification_date'] }}</td>
                        <td>{{ $item['customer']}}</td>
                        <td>{{ $item['post_code'] }}</td>
                        <td>{{ $item['application_id'] }}</td>
                        <td>{{ $item['retailer_reference'] }}</td>
                        <td class="text-right">{{ '&pound;' . number_format($item['order_amount']/100, 2)}}</td>
                        <td>{{ $item['type'] }}</td>
                        <td class="{{($item['deposit'] < 0 ? 'text-danger' : '') }} text-right">{{'&pound;' . number_format($item['deposit']/100, 2)}}</td>
                        <td class="{{($item['loan_amount'] < 0 ? 'text-danger' : '') }} text-right" >{{'&pound;' . number_format($item['loan_amount']/100, 2)}}</td>
                        <td class="{{($item['subsidy'] < 0 ? 'text-danger' : '') }} text-right">{{'&pound;' . number_format($item['subsidy']/100, 2) }}</td>
                        <td class="{{($item['adjustment'] < 0 ? 'text-danger' : '') }} text-right">{{'&pound;' . number_format($item['adjustment']/100, 2)}}</td>
                        <td class="{{($item['settlement_amount'] < 0 ? 'text-danger' : '') }} text-right">{{'&pound;' . number_format($item['settlement_amount']/100, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="12"></td>
                    <td class="{{($aggregate_settlement_total < 0 ? '.text-danger' : '') }} text-right"><strong>{{ '&pound;' . number_format($aggregate_settlement_total/100, 2) }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

@endsection
