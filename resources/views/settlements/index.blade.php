@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>SETTLEMENT REPORTS</h1>
    @include('includes.page.breadcrumb')

    <div class="panel panel-default">

        {{--@include('includes.form.record_counter', ['object' => $settlement_reports])--}}

        <div class="panel-heading"><h4>Settlement Reports</h4></div>
        <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
        <tr>
            {{--TITLES--}}

            <th class="hidden-sm hidden-xs">Report ID</th>
            <th>Settlement Date</th>
            <th class="hidden-sm hidden-xs">Provider</th>
            <th>Amount</th>
            <th class="col-xs-3 col-sm-3 col-md-2 col-lg-1"><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{--FILTERS--}}
            <th class="hidden-sm hidden-xs col-xs-3 col-sm-3 col-md-2 col-lg-1"></th>
            <th class="col-xs-6 col-sm-6 col-md-5 col-lg-4">@include('includes.form.date_range', ['field_start' => 'date_from', 'field_end' => 'date_to', 'placeHolder_from' => date('Y/m/d', strtotime($default_dates[0])), 'placeHolder_to' => date('Y/m/d', strtotime($default_dates[1]))])</th>
            <th class="hidden-sm hidden-xs col-md-3 col-lg-3">@include('includes.form.select', ['field' => 'provider', 'object' => $settlement_reports])</th>
            <th></th>
            <th class="col-xs-3 col-sm-3 col-md-2 col-lg-1">@include('includes.form.filter_buttons')</th>
        </tr>
        {!! Form::close() !!}
        <tr>
        {{-- */$x=0;/* --}}
        @foreach($settlement_reports as $item)
             {{-- */$x++;/* --}}
            <tr>
                <td class="hidden-sm hidden-xs">{{ $item->id }}</td>
                <td>{{ date('d/m/Y', strtotime($item->settlement_date)) }}</td>
                <td class="hidden-sm hidden-xs">{{ $item->provider }}</td>
                <td>{{ '£' . number_format($item->amount/100, 2) }}</td>

                 {{--ACTION BUTTONS --}}
                <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                    @include('includes.form.record_actions', ['id' => $item->id])
                </td>
            </tr>
        @endforeach
        @if($x == 0) <td colspan="5"><em>0 Settlements</em></td> @endif
        </tr>
    </table>
    </div>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {{--{!! $applications->appends(Request::except('page'))->render() !!}--}}

@endsection
