@extends('main', ['large' => 'table-fixed-layout-large'])

@section('content')

    <h1>
        Applications
        <div class="btn-group pull-right">
            {{-- */$params='';/* --}}
            @foreach(Request::all() as $key=>$val) {{-- */$params.="$key=$val&";/*--}} @endforeach
            <a href="{!! Request::url() !!}/?{{$params}}download=csv&amp;limit=5000" class="btn btn-default"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Download CSV</a>
        </div>
    </h1>

    @include('includes.page.breadcrumb', ['over' => [1 => isset($applications[0]->installation->name) ? $applications[0]->installation->name : Request::segment(2)], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])

    <p><strong>{{ $applications->count() }}</strong> Record(s) / <strong>{{ $applications->total() }}</strong> Total</p>
    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}

    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{--TITLES--}}
            <th>ID</th>
            <th>Received</th>
            <th>Current Status</th>
            <th>Retailer Reference</th>
            <th>Finance Group</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Postcode</th>
            <th>Order Amount</th>
            <th>Loan Amount</th>
            <th>Deposit</th>
            <th>Subsidy</th>
            <th>Commission</th>
            <th>Net Settlement</th>
            <th>Location</th>
            <th>Applicant Email</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{--FILTERS--}}
            <th>{!! Form::text('ext_id', Request::only('ext_id')['ext_id'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th class="datepicker-spacing">
                <div style="padding-right: 0px !important; padding-left: 0px !important;" class="col-md-12">
                    <div style="padding-right: 0px !important; padding-left: 2px !important; padding-bottom: 2px !important;">
                        <div class="datepicker">
                            {!! Form::text('date_from', Request::only('date_from')['date_from'] ? Request::only('date_from')['date_from'] : \Carbon\Carbon::today()->format('Y/m/d'), ['id' => 'datepicker_from', 'class' => 'filter form-control', 'placeholder' => date('Y/m/d', strtotime($default_dates['date_from']))]) !!}
                        </div>
                    </div>
                    <div style="padding-right: 0px !important; padding-left: 2px !important;" class="col-md-12">
                        <div class="datepicker">
                            {!! Form::text('date_to', Request::only('date_to')['date_to'], ['id' => 'datepicker_to', 'class' => 'filter form-control', 'placeholder' => date('Y/m/d', strtotime($default_dates['date_to']))]) !!}
                        </div>
                    </div>
                </div>
            </th>
            <th>{!! Form::select('ext_current_status', $ext_current_status, Request::only('ext_current_status')['ext_current_status'], ['class' => 'filter form-control']) !!}</th>
            <th>{!! Form::text('ext_order_reference', Request::only('ext_order_reference')['ext_order_reference'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th>{!! Form::select('ext_finance_option_group', $ext_finance_option_group, Request::only('ext_finance_option_group')['ext_finance_option_group'], ['class' => 'filter form-control']) !!}</th>
            <th>{!! Form::text('ext_customer_first_name', Request::only('ext_customer_first_name')['ext_customer_first_name'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th>{!! Form::text('ext_customer_last_name', Request::only('ext_customer_last_name')['ext_customer_last_name'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th>{!! Form::text('ext_application_address_postcode', Request::only('ext_application_address_postcode')['ext_application_address_postcode'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th><div class="input-group"><span class="input-group-addon" id="basic-addon1">&pound;</span>{!! Form::text('ext_order_amount', Request::only('ext_order_amount')['ext_order_amount'], ['class' => 'filter col-xs-12 pull-down']) !!}</div></th>
            <th><div class="input-group"><span class="input-group-addon" id="basic-addon1">&pound;</span>{!! Form::text('ext_finance_loan_amount', Request::only('ext_finance_loan_amount')['ext_finance_loan_amount'], ['class' => 'filter col-xs-12 pull-down']) !!}</div></th>
            <th><div class="input-group"><span class="input-group-addon" id="basic-addon1">&pound;</span>{!! Form::text('ext_finance_deposit', Request::only('ext_finance_deposit')['ext_finance_deposit'], ['class' => 'filter col-xs-12 pull-down']) !!}</div></th>
            <th><div class="input-group"><span class="input-group-addon" id="basic-addon1">&pound;</span>{!! Form::text('ext_finance_subsidy', Request::only('ext_finance_subsidy')['ext_finance_subsidy'], ['class' => 'filter col-xs-12 pull-down']) !!}</div></th>
            <th><div class="input-group"><span class="input-group-addon" id="basic-addon1">&pound;</span>{!! Form::text('ext_finance_commission', Request::only('ext_finance_commission')['ext_finance_commission'], ['class' => 'filter col-xs-12 pull-down']) !!}</div></th>
            <th><div class="input-group"><span class="input-group-addon" id="basic-addon1">&pound;</span>{!! Form::text('ext_finance_net_settlement', Request::only('ext_finance_net_settlement')['ext_finance_net_settlement'], ['class' => 'filter col-xs-12 pull-down']) !!}</div></th>
            <th>{!! Form::text('ext_fulfilment_location', Request::only('ext_fulfilment_location')['ext_fulfilment_location'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th>{!! Form::text('ext_applicant_email_address', Request::only('ext_applicant_email_address')['ext_applicant_email_address'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th class="text-right">
                <div class="btn-group pull-right">
                    <button type="submit" class="filter btn btn-info btn-xs"> FILTER </button>
                    <button type="button" class="filter btn btn-info dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ Request::url() }}" onclick="">Clear All Filters</a></li>
                        <li><a href="{{ URL::full() }}">Reset Current Changes</a></li>
                    </ul>
                </div>
            </th>
        </tr>
        @forelse($applications as $item)
            <tr>
                <td>{{ $item->ext_id }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                <td>{{ ucwords($item->ext_current_status) }}</td>
                <td>{{ $item->ext_order_reference }}</td>
                <td>{{ $item->ext_finance_option_group }}</td>
                <td>{{ $item->ext_customer_first_name }}</td>
                <td>{{ $item->ext_customer_last_name }}</td>
                <td>{{ $item->ext_application_address_postcode }}</td>
                <td>{{ '&pound;' . number_format($item->ext_order_amount/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item->ext_finance_loan_amount/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item->ext_finance_deposit/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item->ext_finance_subsidy/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item->ext_finance_commission/100, 2) }}</td>
                <td>{{ '&pound;' . number_format($item->ext_finance_net_settlement/100, 2) }}</td>
                <td nowrap>{{ str_limit($item->ext_fulfilment_location, 15) }}</td>
                <td nowrap>{{ $item->ext_applicant_email_address }}</td>

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

                                @if(Auth::user()->can('applications-cancel') && !in_array($item->ext_current_status, ['declined', 'pending_cancellation', 'cancelled']))
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
            <tr><td colspan="16"><em>No records found</em></td></tr>
        @endforelse
    </table>
    {!! Form::close() !!}

    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $applications->appends(Request::all())->render() !!}

@endsection
