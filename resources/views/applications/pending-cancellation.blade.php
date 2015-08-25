@extends('master')

@section('content')

    <h2>Pending Cancellations</h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])

    <div class="panel panel-default">
        <div class="panel-heading"><h4>Pending Cancellations</h4></div>
        <table class="table table-bordered table-striped table-hover">
        {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
        <tr>
            {{--TITLES--}}
            <th>Retailer Ref.</th>
            <th>Name</th>
            <th>Cancelled Reason</th>
            <th>Requested</th>
        </tr>
        {!! Form::close() !!}
        @foreach($applications as $item)
            <tr>
                <td>{{ $item->order['reference'] }}</td>
                <td>{{ trim($item->customer['title'] . ' ' . $item->customer['first_name'] . ' '.  $item->customer['last_name']) }}</td>
                <td>{{ $item->cancellation['description'] }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($item->cancellation['requested_date'])) }}</td>
            </tr>
        @endforeach
        @if($applications->count() == 0) <td colspan="4"><em>0 Records</em></td> @endif
    </table>
    </div>

@endsection
