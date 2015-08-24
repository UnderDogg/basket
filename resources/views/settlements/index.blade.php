@extends('master')

@section('content')

    <h1>SETTLEMENT REPORTS</h1>

    @include('includes.form.record_counter', ['object' => $settlementReports])
    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}

    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th>Report ID</th>
            <th>Settlement Date</th>
            <th>Provider</th>
            <th>Amount</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th></th>
            <th>@include('includes.form.date_range', ['field_start' => 'date_from', 'field_end' => 'date_to', 'placeHolder_from' => date('Y/m/d', strtotime($defaultDates['date_from'])), 'placeHolder_to' => date('Y/m/d', strtotime($defaultDates['date_to']))])</th>
            <th>@include('includes.form.select', ['field' => 'provider', 'object' => $settlementReports])</th>
            <th></th>
            <th>@include('includes.form.filter_buttons')</th>
        </tr>
        @forelse($settlementReports as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ date('d/m/Y', strtotime($item->settlement_date)) }}</td>
                <td>{{ $item->provider }}</td>
                <td>{{ '&pound;' . number_format($item->amount/100, 2) }}</td>

                 {{-- ACTION BUTTONS --}}
                <td class="text-right">
                    @include('includes.form.record_actions', ['id' => $item->id])
                </td>
            </tr>
        @empty
            <tr><td colspan="5"><em>No records found</em></td></tr>
        @endforelse
    </table>

    {!! Form::close() !!}

@endsection
