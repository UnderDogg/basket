@extends('main')

@section('content')

    <h1>
        SETTLEMENT REPORT
        <div class="btn-group pull-right">
            <a href="{!! Request::url() !!}/?download=csv&limit=5000" class="btn btn-default"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Download CSV</a>
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

            <th class="hidden-sm hidden-xs">Order Date</th>
            <th>Customer</th>
            <th>Postcode</th>
            <th>Merc. Ref.</th>
            <th>Loan Amount</th>
            <th>Deposit</th>
            <th>Order Amount</th>
            <th>Subsidy</th>
            <th>Adjustment</th>
            <th>Net</th>

            {{--<th class="col-xs-3 col-sm-3 col-md-2 col-lg-1"><span class="pull-right">Actions</span></th>--}}
        </tr>
        {!! Form::close() !!}
        <tr>
        {{-- */$x=0;/* --}}
        @foreach($settlementReport['settlements'] as $item)
             {{-- */$x++;/* --}}
            <tr>
                <td class="hidden-sm hidden-xs">{{ date('d/m/Y', strtotime($item['captured_date'])) }}</td>
                <td>{{ $item['application_data']['ext_customer_title'] . ' ' . $item['application_data']['ext_customer_first_name'] . ' ' . $item['application_data']['ext_customer_last_name'] }}</td>
                <td>{{ $item['application_data']['ext_application_address_postcode'] }}</td>
                <td>{{ $item['application_data']['ext_order_reference'] }}</td>
                <td>{{ '&pound;' . number_format($item['application_data']['ext_finance_loan_ammount']/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item['application_data']['ext_finance_deposit']/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item['order_amount']/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item['subsidy']/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item['adjustment']/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item['net']/100, 2) }}</td>

                 {{--ACTION BUTTONS --}}
                {{--<td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">--}}
                    {{--@include('includes.form.record_actions', ['id' => $item->id])--}}
                {{--</td>--}}
            </tr>
        @endforeach
        <tr>
            <td colspan="6"></td>
            <td><strong>{{ '&pound;' . number_format($settlementReport['sum_order_amount']/100, 2) }}</strong></td>
            <td><strong>{{ '&pound;' . number_format($settlementReport['sum_subsidy']/100, 2) }}</strong></td>
            <td><strong>{{ '&pound;' . number_format($settlementReport['sum_adjustment']/100, 2) }}</strong></td>
            <td><strong>{{ '&pound;' . number_format($settlementReport['sum_net']/100, 2) }}</strong></td>
        </tr>
    </table>
    </div>

@endsection
