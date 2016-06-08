@extends('main')

@section('content')

    <h1>
        SETTLEMENT REPORT
        <div class="btn-group pull-right">
            <a href="{!! Request::url() !!}/?download=csv&amp;limit=5000" class="btn btn-default"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Download CSV</a>
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
        <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
        <tr>
            {{--TITLES--}}
            <th>Order Date</th>
            <th>Notification Date</th>
            <th>Customer</th>
            <th>Postcode</th>
            <th>Retailer Reference</th>
            <th>Order Amount</th>
            <th>Type</th>
            <th>Deposit</th>
            <th>Loan Amount</th>
            <th>Subsidy</th>
            <th>Adjustment</th>
            <th>Net</th>
        </tr>
        {!! Form::close() !!}
        <tr>
        @foreach($settlementReport['settlements'] as $item)
            <tr>
                <td>{{ date('d/m/Y', strtotime($item['received_date'])) }}</td>
                <td>{{ date('d/m/Y', strtotime($item['captured_date'])) }}</td>
                <td>{{ $item['customer_name']}}</td>
                <td>{{ $item['application_postcode'] }}</td>
                <td>{{ $item['order_reference'] }}</td>
                <td>{{ '&pound;' . number_format($item['order_amount']/100, 2)}}</td>
                <td>{{ $item['type'] }}</td>
                <td class="{{($item['deposit'] < 0 ? 'text-danger' : '') }}">{{ money_format("%.2n",$item['deposit']/100) }}</td>
                <td class="{{($item['loan_amount'] < 0 ? 'text-danger' : '') }}" >{{money_format("%.2n",$item['loan_amount']/100) }}</td>
                <td class="{{($item['subsidy'] < 0 ? 'text-danger' : '') }}">{{money_format("%.2n",$item['subsidy']/100) }}</td>
                <td class="{{($item['adjustment'] < 0 ? 'text-danger' : '') }}">{{money_format("%.2n",$item['adjustment']/100) }}</td>
                <td class="{{($item['net'] < 0 ? 'text-danger' : '') }}">{{money_format("%.2n",$item['net']/100) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="11"></td>
            <td class="{{($settlementReport['sum_net'] < 0 ? '.text-danger' : '') }}"><strong>{{ '&pound;' . number_format($settlementReport['sum_net']/100, 2) }}</strong></td>
        </tr>
    </table>
    </div>

@endsection
