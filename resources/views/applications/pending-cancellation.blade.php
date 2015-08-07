@extends('master')

@section('content')


    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>Pending Cancellations</h1>
    @include('includes.page.breadcrumb')

    <div class="panel panel-default">
        @include('includes.form.record_counter', ['object' => $applications])

        <div class="panel-heading"><h4>Pending Cancellations</h4></div>
        <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
        <tr>
            {{--TITLES--}}
            <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">ID</th>
            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2">Requested</th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1">Name</th>
            <th class="hidden-xs hidden-xs hidden-md col-lg-1">Cancelled Reason</th>
            <th class="hidden-xs hidden-xs hidden-md col-lg-1">Retailer Reference</th>
            <th class="col-xs-3 col-sm-3 col-md-2 col-lg-2 text-right"><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{--FILTERS--}}
            <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></th>
            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2"></th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1"></th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1"></th>
            <th class="hidden-xs hidden-xs hidden-md col-lg-1"></th>
            <th class="col-xs-2 col-sm-3 col-md-1 col-lg-1"></th>
            <th class="hidden-xs hidden-sm col-md-1 col-lg-1"></th>
            <th class="col-xs-3 col-sm-3 col-md-2 col-lg-2 text-right"></th>
        </tr>
        {!! Form::close() !!}
        {{-- */$x=0;/* --}}
        @foreach($applications as $item)
            {{-- */$x++;/* --}}
            <tr>
                <td class="col-xs-1 col-sm-1  col-md-1 col-lg-1">{{ $item->id }}</td>
                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2">{{ date('d/m/Y', strtotime($item->cancellation->requested_date)) }}</td>
                <td class="hidden-xs hidden-sm col-md-1 col-lg-1">{{ $item->id->customer->first_name . ' '.  $item->id->csutomer->last_name }}</td>
                <td class="hidden-xs hidden-xs hidden-md col-lg-1">{{ $item->cancellation->description }}</td>
                <td class="col-xs-2 col-sm-3 col-md-1 col-lg-1">{{ $item->order->reference }}</td>

                {{-- ACTION BUTTONS --}}
                <td class="col-xs-3 col-sm-3 col-md-2 col-lg-2 text-right">&nbsp;</td>
            </tr>
        @endforeach
        @if($x == 0) <td colspan="11"><em>0 Records</em></td> @endif
    </table>
    </div>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $applications->appends(Request::except('page'))->render() !!}

@endsection
