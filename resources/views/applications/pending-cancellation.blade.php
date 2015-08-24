@extends('master')

@section('content')

    <h2>Pending Cancellations</h2>

    @include('includes.form.record_counter', ['object' => $applications])
    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}

    <table class="table table-bordered table-striped table-hover">
        <tr>
            {{-- TITLES --}}
            <th>Retailer Ref.</th>
            <th>Name</th>
            <th>Cancelled Reason</th>
            <th>Requested</th>
        </tr>
    @forelse($applications as $item)
        <tr>
            <td>{{ $item->order['reference'] }}</td>
            <td>{{ trim($item->customer['title'] . ' ' . $item->customer['first_name'] . ' '.  $item->customer['last_name']) }}</td>
            <td>{{ $item->cancellation['description'] }}</td>
            <td>{{ date('d/m/Y H:i', strtotime($item->cancellation['requested_date'])) }}</td>
        </tr>
    @empty
        <tr><td colspan="4"><em>No records found</em></td></tr>
    @endforelse
    </table>

    {!! Form::close() !!}

@endsection
