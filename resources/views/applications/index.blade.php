@extends('master')

@section('content')


    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>APPLICATIONS</h1>
    @include('includes.page.breadcrumb')

    <div class="panel panel-default">
        @include('includes.form.record_counter', ['object' => $applications])

        <div class="panel-heading"><h4>Locations</h4></div>
        <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
        <tr>
            {{--TITLES--}}

            <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">ID</th>
            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2">Received</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">Current Status</th>
            <th class="hidden-xs hidden-xs hidden-md col-lg-1">Retailer Reference</th>
            <th class="col-xs-2 col-sm-3 col-md-1 col-lg-1">Order Amount</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">Loan Amount</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">Deposit</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">Subsidy</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">Net Settlement</th>
            <th class="hidden-xs hidden-sm hidden-md col-lg-1">Location</th>
            <th class="col-xs-3 col-sm-3 col-md-2 col-lg-2 text-right"><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{--FILTERS--}}
            <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">@include('includes.form.input', ['field' => 'ext_id'])</th>
            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2">@include('includes.form.date_range', ['field_start' => 'date_from', 'field_end' => 'date_to', 'placeHolder_from' => date('Y/m/d', strtotime($default_dates['date_from'])), 'placeHolder_to' => date('Y/m/d', strtotime($default_dates['date_to']))])</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">@include('includes.form.select', ['field' => 'ext_current_status', 'object' => $applications])</th>
            <th class="hidden-xs hidden-xs hidden-md col-lg-1">@include('includes.form.input', ['field' => 'ext_order_reference'])</th>
            <th class="col-xs-2 col-sm-3 col-md-1 col-lg-1">@include('includes.form.input_with_symbol', ['field' => 'ext_finance_order_amount', 'symbol' => '£'])</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">@include('includes.form.input_with_symbol', ['field' => 'ext_finance_loan_amount', 'symbol' => '£'])</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">@include('includes.form.input_with_symbol', ['field' => 'ext_finance_deposit', 'symbol' => '£'])</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">@include('includes.form.input_with_symbol', ['field' => 'ext_finance_subsidy', 'symbol' => '£'])</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">@include('includes.form.input_with_symbol', ['field' => 'ext_finance_net_settlement', 'symbol' => '£'])</th>
            <th class="hidden-xs hidden-sm hidden-md col-lg-1">@include('includes.form.input', ['field' => 'ext_fulfilment_location'])</th>
            <th class="col-xs-3 col-sm-3 col-md-2 col-lg-2 text-right">@include('includes.form.filter_buttons')</th>
        </tr>
        {!! Form::close() !!}
        {{-- */$x=0;/* --}}
        @foreach($applications as $item)
            {{-- */$x++;/* --}}
            <tr>
                <td class="col-xs-1 col-sm-1  col-md-1 col-lg-1">{{ $item->ext_id }}</td>
                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                <td class="hidden-xs hidden-sm col-md-1 col-lg-1">{{ ucwords($item->ext_current_status) }}</td>
                <td class="hidden-xs hidden-xs hidden-md col-lg-1">{{ $item->ext_order_reference }}</td>
                <td class="col-xs-2 col-sm-3 col-md-1 col-lg-1">{{ '£' . number_format($item->ext_finance_order_amount/100, 2) }}</td>
                <td class="hidden-xs hidden-sm col-md-1 col-lg-1">{{ '£' . number_format($item->ext_finance_loan_amount/100, 2) }}</td>
                <td class="hidden-xs hidden-sm col-md-1 col-lg-1">{{ '£' . number_format($item->ext_finance_deposit/100, 2) }}</td>
                <td class="hidden-xs hidden-sm col-md-1 col-lg-1">{{ '£' . number_format($item->ext_finance_subsidy/100, 2) }}</td>
                <td class="hidden-xs hidden-sm col-md-1 col-lg-1">{{ '£' . number_format($item->ext_finance_net_settlement/100, 2) }}</td>
                <td nowrap class="hidden-xs hidden-sm hidden-md col-lg-1">{{ str_limit($item->ext_fulfilment_location, 15) }}</td>


                {{-- ACTION BUTTONS --}}
                <td class="col-xs-3 col-sm-3 col-md-2 col-lg-2 text-right">
                    @include('includes.form.record_actions', ['id' => $item->id,
                        'actions' => ['edit' => 'Edit', 'fulfil' => 'Fulfil', 'request-cancellation' => 'Request Cancellation']
                    ])
                </td>
            </tr>
        @endforeach
        @if($x == 0) <td colspan="11"><em>0 Applications</em></td> @endif
    </table>
    </div>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $applications->appends(Request::except('page'))->render() !!}

@endsection
