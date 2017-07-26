@extends('main')

@section('content')

    <h1>Settlement Reports</h1>
    @include('includes.page.breadcrumb', ['over' => [1 => isset(current($local)->installation->merchant->name) ? current($local)->installation->merchant->name : Request::segment(2)], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])
    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}

    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th class="hidden-xs">Report ID</th>
            <th>Settlement Date</th>
            <th>Lender</th>
            <th>Amount</th>
            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-right"><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th class="hidden-xs"></th>
            <th>
                <div style="padding-right: 0px !important; padding-left: 0px !important;" class="col-md-12">
                    <div style="padding-right: 0px !important; padding-left: 2px !important; padding-bottom: 2px !important;">
                        <div class="datepicker">
                            {!! Form::text('date_from', Request::only('date_from')['date_from'], ['id' => 'datepicker_from', 'class' => 'filter form-control', 'placeholder' => date('Y/m/d', strtotime($default_dates['date_from']))]) !!}
                        </div>
                    </div>
                    <div style="padding-right: 0px !important; padding-left: 2px !important;" class="col-md-12">
                        <div class="datepicker">
                            {!! Form::text('date_to', Request::only('date_to')['date_to'], ['id' => 'datepicker_to', 'class' => 'filter form-control', 'placeholder' => date('Y/m/d', strtotime($default_dates['date_to']))]) !!}
                        </div>
                    </div>
                </div>
            </th>
            <th>{!! Form::select('provider', $provider, Request::only('provider')['provider'], ['class' => 'filter form-control']) !!}</th>
            <th></th>
            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-right">
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
        @forelse($settlement_reports as $item)
            <tr>
                <td class="hidden-xs">{{ $item->id }}</td>
                <td>{{ date('d/m/Y', strtotime($item->settlement_date)) }}</td>
                <td>{{ $item->provider }}</td>
                <td>{{ '&pound;' . number_format($item->amount/100, 2) }}</td>

                 {{-- ACTION BUTTONS --}}
                <td class="text-right">
                    <div class="btn-group">
                        <a href="{{ Request::URL() }}/{{ $item->id }}?date={{ $item->settlement_date }}&amp;provider={{ $item->provider }}" type="button" class="btn btn-default btn-xs"> View </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5"><em>No records found</em></td></tr>
        @endforelse
    </table>

    {!! Form::close() !!}
    {{ $settlement_reports->links() }}

@endsection
