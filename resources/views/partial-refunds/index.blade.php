@extends('master')

@section('content')

    <h1>PARTIAL REFUNDS</h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])

    <div class="panel panel-default">

        {{--@include('includes.form.record_counter', ['object' => $settlement_reports])--}}

        <div class="panel-heading"><h4>Partial Refunds</h4></div>
        <table class="table table-bordered table-striped table-hover">
            {{-- TABLE HEADER WITH FILTERS --}}
            {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
            <tr>
                {{--TITLES--}}

                <th class="hidden-sm hidden-xs">ID</th>
                <th>Application ID</th>
                <th>Status</th>
                <th class="text-right">Refund Amount</th>
                <th>Effective Date</th>
                <th>Requested Date</th>
                <th class="col-xs-3 col-sm-3 col-md-2 col-lg-1"><span class="pull-right">Actions</span></th>
            </tr>
            <tr>
                {{--FILTERS--}}
                <th class="hidden-sm hidden-xs col-xs-3 col-sm-3 col-md-2 col-lg-1"></th>
                <th></th>
                <th>@include('includes.form.select', ['field' => 'status', 'object' => $settlement_reports])</th>
                <th></th>
                <th></th>
                <th></th>
                <th class="col-xs-3 col-sm-3 col-md-2 col-lg-1">@include('includes.form.filter_buttons')</th>
            </tr>
            {!! Form::close() !!}

            {{-- */$x=0;/* --}}
            @foreach($settlement_reports as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td class="hidden-sm hidden-xs">{{ $item->id }}</td>
                    <td>{{ $item->application }}</td>
                    <td>{{ $item->status }}</td>
                    <td class="text-right">{{ money_format('%.2n', $item->refund_amount/100) }}</td>
                    <td>{{ DateTime::createFromFormat('Y-m-d', $item->effective_date)->format('d/m/Y') }}</td>
                    <td>{{ DateTime::createFromFormat('Y-m-d', $item->requested_date)->format('d/m/Y') }}</td>

                    {{--ACTION BUTTONS --}}
                    <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                        @include('includes.form.record_actions', ['id' => $item->id])
                    </td>
                </tr>
            @endforeach
            @if($x == 0)<tr><td colspan="7"><em>0 Partial Refunds</em></td></tr>@endif

        </table>
    </div>

@endsection
