@extends('main')

@section('content')

    <h1>Partial Refunds</h1>
    @include('includes.page.breadcrumb', ['over' => [1 =>isset(current($local)->installation->merchant->name) ? current($local)->installation->merchant->name : Request::segment(2)], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])
    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th>ID</th>
            <th>Application ID</th>
            <th>Status</th>
            <th>Refund Amount</th>
            <th>Effective Date</th>
            <th>Requested Date</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th></th>
            <th></th>
            <th>{!! Form::select('status', $status, Request::only('status')['status'], ['class' => 'filter form-control']) !!}</th>
            <th></th>
            <th></th>
            <th></th>
            <th>
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
        @forelse($partial_refunds as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->application }}</td>
                <td>{{ ucwords($item->status) }}</td>
                <td>{{ money_format('%.2n', $item->refund_amount/100) }}</td>
                <td>{{ DateTime::createFromFormat('Y-m-d', $item->effective_date)->format('d/m/Y') }}</td>
                <td>{{ DateTime::createFromFormat('Y-m-d', $item->requested_date)->format('d/m/Y') }}</td>

                {{-- ACTION BUTTONS --}}
                <td class="text-right">

                    <div class="btn-group">
                        <a href="{{Request::URL()}}/{{$item->id}}" type="button" class="btn btn-default btn-xs"> View </a>
                        <button type="button" class="btn btn-default dropdown-toggle btn-xs @if(empty($local[$item->application])) disabled @endif" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            @if($local[$item->application])
                                <li><a href="/installations/{{$local[$item->application]->installation_id}}/applications/{{$local[$item->application]->id}}"> View Application </a></li>
                            @endif
                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7"><em>No records found</em></td></tr>
        @endforelse
    </table>
    {!! Form::close() !!}


@endsection
