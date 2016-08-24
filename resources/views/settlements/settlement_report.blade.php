@extends('main')

@section('content')

    <h1>
        Settlement Report
        <div class="btn-toolbar pull-right">
            <a href="{{Request::url()}}/?download=csv&amp;source=view_data&amp;filename=export_view_filename&amp;limit=5000" class="btn btn-default"><span class="glyphicon glyphicon-save"></span> Download Report</a>
            <a href="{!! Request::url() !!}/?download=csv&amp;source=api_data&amp;filename=export_api_filename&amp;limit=5000" class="btn btn-default"><span class="glyphicon glyphicon-save" aria-hidden="true"></span>Download Raw</a>
        </div>
    </h1>
    @include('includes.page.breadcrumb', ['over' => [1 => isset($installation->installation->merchant->name) ? $installation->installation->merchant->name : Request::segment(2)], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])
    <h3><span>Amount: {{ '&pound;' . number_format($settlementReport['amount']/100, 2) }}</span></h3>
    <h5>
        <span>Date: {{ date('d/m/Y', strtotime($settlementReport['settlement_date'])) }}</span> |
        <span>Lender: {{ ucwords($settlementReport['provider']) }}</span>
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
                @foreach($settlementReport['settlements'] as $item)
                    <tr>
                        <td>{{ date('d/m/Y', strtotime($item['received_date'])) }}</td>
                        <td>{{ date('d/m/Y', strtotime($item['captured_date'])) }}</td>
                        <td>{{ $item['customer_name']}}</td>
                        <td>{{ $item['application_postcode'] }}</td>
                        <td>{{ $item['application'] }}</td>
                        <td>{{ $item['order_reference'] }}</td>
                        <td class="text-right">{{ '&pound;' . number_format($item['order_amount']/100, 2)}}</td>
                        <td>{{ $item['type'] }}</td>
                        <td class="{{($item['deposit'] < 0 ? 'text-danger' : '') }} text-right">{{'&pound;' . number_format($item['deposit']/100,2)}}</td>
                        <td class="{{($item['loan_amount'] < 0 ? 'text-danger' : '') }} text-right" >{{'&pound;' . number_format($item['loan_amount']/100,2)}}</td>
                        <td class="{{($item['subsidy'] < 0 ? 'text-danger' : '') }} text-right">{{'&pound;' . number_format($item['subsidy']/100,2) }}</td>
                        <td class="{{($item['adjustment'] < 0 ? 'text-danger' : '') }} text-right">{{'&pound;' . number_format($item['adjustment']/100,2)}}</td>
                        <td class="{{($item['net'] < 0 ? 'text-danger' : '') }} text-right">{{'&pound;' . number_format($item['net']/100,2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="12"></td>
                    <td class="{{($settlementReport['sum_net'] < 0 ? '.text-danger' : '') }} text-right"><strong>{{ '&pound;' . number_format($settlementReport['sum_net']/100, 2) }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

@endsection
