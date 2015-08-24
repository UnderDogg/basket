@extends('master')

@section('content')

    <h1>PARTIAL REFUNDS</h1>

    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}

    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th>ID</th>
            <th>Application ID</th>
            <th>Status</th>
            <th class="text-right">Refund Amount</th>
            <th>Effective Date</th>
            <th>Requested Date</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th></th>
            <th></th>
            <th>@include('includes.form.select', ['field' => 'status', 'object' => $partialRefunds])</th>
            <th></th>
            <th></th>
            <th></th>
            <th>@include('includes.form.filter_buttons')</th>
        </tr>
        @forelse($partialRefunds as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->application }}</td>
                <td>{{ $item->status }}</td>
                <td class="text-right">{{ money_format('%.2n', $item->refund_amount/100) }}</td>
                <td>{{ DateTime::createFromFormat('Y-m-d', $item->effective_date)->format('d/m/Y') }}</td>
                <td>{{ DateTime::createFromFormat('Y-m-d', $item->requested_date)->format('d/m/Y') }}</td>

                {{-- ACTION BUTTONS --}}
                <td class="text-right">
                    @include('includes.form.record_actions', ['id' => $item->id])
                </td>
            </tr>
        @empty
            <tr><td colspan="7"><em>No records found</em></td></tr>
        @endforelse
    </table>
    {!! Form::close() !!}


@endsection
