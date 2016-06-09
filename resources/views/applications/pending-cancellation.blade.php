@extends('main')

@section('content')

    <h1>Pending Cancellations</h1>
    @include('includes.page.breadcrumb', ['over' => [1 => $installation->name], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])

    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}

    <table class="table table-bordered table-striped table-hover">
        <tr>
            {{-- TITLES --}}
            <th>Retailer Ref.</th>
            <th>Name</th>
            <th>Cancellation Fee</th>
            <th>Cancelled Reason</th>
            <th>Requested</th>
            <th></th>
        </tr>
    @forelse($applications as $item)
        <tr>
            <td>{{ $item->ext_order_reference }}</td>
            <td>{{ trim($item->ext_customer_title . ' ' . $item->ext_customer_first_name . ' '.  $item->ext_customer_last_name) }}</td>
            <td>{{ '&pound;' . number_format($item->ext_cancellation_fee_amount/100, 2) }}</td>
            <td>{{ $item->ext_cancellation_description }}</td>
            <td>{{ date('d/m/Y H:i', strtotime($item->ext_cancellation_requested_date)) }}</td>

            <td class="text-right">
                <div class="btn-group">
                    <a href="/installations/{{$item->installation->id}}/applications/{{$item->id}}" type="button" class="btn btn-default btn-xs"> View </a>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="6"><em>No records found</em></td></tr>
    @endforelse
    </table>

    {!! Form::close() !!}

@endsection
