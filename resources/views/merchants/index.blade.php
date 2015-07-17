@extends('master')

@section('content')
    {{-- OVERLAY MESSAGES --}}
    {{--@include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])--}}

    <h1>MERCHANTS
        <a href="{{ url('/merchants/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New Merchant</a>
    </h1>
    @include('includes.page.breadcrumb', ['override1'=>'','override2'=>'','override3'=>'','override4'=>''])
    <div class="panel panel-default">

        @include('includes.form.record_counter', ['object' => $merchants])

        <div class="panel-heading"><h4>Merchants</h4></div>
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>Name</th>
                <th class="hidden-xs hidden-sm">Company Name</th>
                <th class="hidden-xs hidden-sm">Min. Amount Settled</th>
                <th>Linked</th>
                <th>Actions</th>
            </tr>
            {{-- */$x=0;/* --}}
            @foreach($merchants as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="hidden-xs hidden-sm">{{ $item->ext_company_name }}</td>
                    <td class="hidden-xs hidden-sm">{{ $item->ext_minimum_amount_settled }}</td>
                    <td>{{ $item->linked }}</td>

                    {{-- ACTION BUTTONS --}}
                    <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                        @include('includes.form.record_buttons', ['record' => $item, 'crudName' => 'merchants'])
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $merchants->appends(Request::except('page'))->render() !!}
@endsection
