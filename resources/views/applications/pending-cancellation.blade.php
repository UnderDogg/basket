@extends('main')

@section('content')

    <h1>Pending Cancellations</h1>
    @include('includes.page.breadcrumb', ['over' => [1 => isset($application[0]->installation->name) ? $application[0]->installation->name : Request::segment(2)], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])

    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}

    <table class="table table-bordered table-striped table-hover">
        <tr>
            {{-- TITLES --}}
            <th>Retailer Ref.</th>
            <th>Name</th>
            {{--<th>Cancelled Reason</th>--}}
            {{--<th>Requested</th>--}}
            <th></th>
        </tr>
    @forelse($applications as $item)
        <tr>
            <td>{{ $item->order['reference'] }}</td>
            <td>{{ trim($item->customer['title'] . ' ' . $item->customer['first_name'] . ' '.  $item->customer['last_name']) }}</td>
            {{--<td>{{ $item->cancellation['description'] }}</td>--}}
            {{--<td>{{ date('d/m/Y H:i', strtotime($item->cancellation['requested_date'])) }}</td>--}}

            <td class="text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle btn-xs @if(empty($local[$item->id])) disabled @endif" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        {{dd($item)}}
                        @if($local[$item->id])
                            <li><a href="/installations/{{$local[$item->id]['installation']}}/applications/{{$local[$item->id]['id']}}"> View Application </a></li>
                        @endif
                    </ul>
                    <a href=/installations/{{Request::segment(2)}}/applications/{{$local[$item->id]}} type="button" class="btn btn-default btn-xs"> View </a>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="4"><em>No records found</em></td></tr>
    @endforelse
    </table>

    {!! Form::close() !!}

@endsection
