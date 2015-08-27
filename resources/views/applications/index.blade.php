@extends('master')

@section('content')

    <h1>
        APPLICATIONS
        <div class="btn-group pull-right">
            {{-- */$params='';/* --}}
            @foreach(Request::all() as $key=>$val) {{-- */$params.="$key=$val&";/*--}} @endforeach
            <a href="{!! Request::url() !!}/?{{$params}}download=csv&limit=5000" class="btn btn-default"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Download CSV</a>
        </div>
    </h1>

    @include('includes.page.breadcrumb', ['over' => [1 => isset($applications[0]->installation->name) ? $applications[0]->installation->name : Request::segment(2)], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])
    @include('includes.form.record_counter', ['object' => $applications])
    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}

    <table class="table table-bordered table-striped table-hover table-fixed-layout-large">
        {{-- TABLE HEADER WITH FILTERS --}}
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
            <th>@include('includes.form.input', ['field' => 'ext_application_address_postcode'])</th>

            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_order_amount', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_loan_amount', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_deposit', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_subsidy', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input_with_symbol', ['field' => 'ext_finance_net_settlement', 'symbol' => '&pound;'])</th>
            <th>@include('includes.form.input', ['field' => 'ext_fulfilment_location'])</th>
            <th class="text-right">@include('includes.form.filter_buttons')</th>
        </tr>
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

                    <div class="btn-group">
                        <a href="{{Request::URL()}}/{{$item->id}}" type="button" class="btn btn-default btn-xs"> View </a>

                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" {!! in_array($item->ext_current_status, ['converted', 'fulfilled', 'complete'])?'':'disabled="disabled"' !!}>
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">

                                @if(Auth::user()->can('applications-fulfil') && $item->ext_current_status === 'converted')
                                <li><a href="{{Request::URL()}}/{{$item->id}}/fulfil">Fulfil</a></li>
                                @endif

                                @if(Auth::user()->can('applications-cancel') && in_array($item->ext_current_status, ['converted', 'fulfilled', 'complete']))
                                <li><a href="{{Request::URL()}}/{{$item->id}}/request-cancellation">Request Cancellation</a></li>
                                @endif

                                @if(Auth::user()->can('applications-refund') && in_array($item->ext_current_status, ['converted', 'fulfilled', 'complete']))
                                <li><a href="{{Request::URL()}}/{{$item->id}}/partial-refund">Partial Refund</a></li>
                                @endif

                            </ul>
                    </div>

                </td>
            </tr>
        @empty
            <tr><td colspan="14"><em>No records found</em></td></tr>
        @endforelse
    </table>
    {!! Form::close() !!}

    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $applications->appends(Request::except('page'))->render() !!}

@endsection
