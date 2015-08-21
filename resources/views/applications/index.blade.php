@extends('master')

@section('content')

    <h1>APPLICATIONS</h1>
        <div class="scroll-x-overflow">
        @include('includes.form.record_counter', ['object' => $applications])
        <table class="table table-bordered table-striped table-hover table-fixed-layout-large">
        {{-- TABLE HEADER WITH FILTERS --}}
        {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
        <tr>
            {{--TITLES--}}

            <th>ID</th>
            <th>Received</th>
            <th>Current Status</th>
            <th>Retailer Reference</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Postcode</th>
            <th>Order Amount</th>
            <th>Loan Amount</th>
            <th>Deposit</th>
            <th>Subsidy</th>
            <th>Net Settlement</th>
            <th>Location</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{--FILTERS--}}
            <th>@include('includes.form.input', ['field' => 'ext_id'])</th>
            <th>@include('includes.form.date_range', ['field_start' => 'date_from', 'field_end' => 'date_to', 'placeHolder_from' => date('Y/m/d', strtotime($default_dates['date_from'])), 'placeHolder_to' => date('Y/m/d', strtotime($default_dates['date_to']))])</th>
            <th>@include('includes.form.select', ['field' => 'ext_current_status', 'object' => $applications])</th>
            <th>@include('includes.form.input', ['field' => 'ext_order_reference'])</th>

            {{--ADDED COLUMNS --}}
            <th>@include('includes.form.input', ['field' => 'ext_customer_first_name'])</th>
            <th>@include('includes.form.input', ['field' => 'ext_customer_last_name'])</th>
            <th>@include('includes.form.input', ['field' => 'ext_customer_postcode'])</th>

            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_order_amount', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_loan_amount', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_deposit', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_subsidy', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_net_settlement', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input', ['field' => 'ext_fulfilment_location'])</th>
            <th class="text-right">@include('includes.form.filter_buttons')</th>
        </tr>
        {!! Form::close() !!}
            @forelse($applications as $item)
                <tr>
                    <td>{{ $item->ext_id }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                    <td>{{ ucwords($item->ext_current_status) }}</td>
                    <td>{{ $item->ext_order_reference }}</td>
                    <td>{{ $item->ext_customer_first_name }}</td>
                    <td>{{ $item->ext_customer_last_name }}</td>
                    <td>{{ $item->ext_application_address_postcode }}</td>
                    <td>{{ '&pound;' . number_format($item->ext_order_amount/100, 2) }}</td>
                    <td>{{ '&pound;' . number_format($item->ext_finance_loan_amount/100, 2) }}</td>
                    <td>{{ '&pound;' . number_format($item->ext_finance_deposit/100, 2) }}</td>
                    <td>{{ '&pound;' . number_format($item->ext_finance_subsidy/100, 2) }}</td>
                    <td>{{ '&pound;' . number_format($item->ext_finance_net_settlement/100, 2) }}</td>
                    <td nowrap>{{ str_limit($item->ext_fulfilment_location, 15) }}</td>

                    {{-- ACTION BUTTONS --}}
                    <td class="text-right">
                        @include('includes.form.record_actions', ['id' => $item->id,
                            'actions' => ['edit' => 'Edit', 'fulfil' => 'Fulfil', 'request-cancellation' => 'Request Cancellation', 'partial-refund' => 'Partial Refund']
                        ])
                    </td>
                </tr>
            @empty
                <td colspan="14"><em>0 Applications</em></td>
            @endforelse
        </table>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $applications->appends(Request::except('page'))->render() !!}

@endsection
