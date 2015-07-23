@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>LOCATIONS
        <a href="{{ url('/locations/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New Location</a>
    </h1>
    @include('includes.page.breadcrumb')

    <div class="panel panel-default">

        @include('includes.form.record_counter', ['object' => $locations])

        <div class="panel-heading"><h4>Locations</h4></div>
        <table class="table table-bordered table-striped table-hover">
            {{-- TABLE HEADER WITH FILTERS --}}
            {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
            <tr>
                {{--TITLES--}}
                <th class="hidden-xs hidden-sm">Reference</th>
                <th>Name</th>
                <th class="hidden-xs hidden-sm">Installation</th>
                <th>Active</th>
                <th><span class="pull-right">Actions</span></th>
            </tr>
            <tr>
                {{--FILTERS--}}
                <th class="hidden-xs hidden-sm">@include('includes.form.input', ['field' => 'reference'])</th>
                <th>@include('includes.form.input', ['field' => 'name'])</th>
                <th class="hidden-xs hidden-sm">@include('includes.form.associate_select', [
                    'field' => 'installation_id',
                    'object' => $locations,
                    'associate'=>'installation',
                    'associateField'=>'name',
                ])</th>
                <th>@include('includes.form.bool_select', ['field' => 'active', 'object' => $locations,'false'=>'Inactive','true'=>'Active'])</th>
                <th>@include('includes.form.filter_buttons')</th>
            </tr>
            {!! Form::close() !!}
            {{-- */$x=0;/* --}}
            @foreach($locations as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td class="hidden-xs hidden-sm">{{ $item->reference }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="hidden-xs hidden-sm">{{ $item->installation->name }}</td>
                    <td class="col-sm-2 col-md-1">
                        @if( $item->active == 0 )
                            <span class="label label-danger pull-right"><i class="glyphicon glyphicon-remove"></i></span>
                        @elseif( $item->active == 1 )
                            <span class="label label-success pull-right"><i class="glyphicon glyphicon-ok"></i></span>
                        @endif
                    </td>

                    {{-- ACTION BUTTONS --}}
                    <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                        @include('includes.form.record_actions', ['id' => $item->id,
                            'actions' => ['edit' => 'Edit', 'delete' => 'Delete']
                        ])
                    </td>
                </tr>
            @endforeach
            @if($x == 0) <td colspan="5"><em>0 Locations</em></td> @endif
        </table>
    </div>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $locations->appends(Request::except('page'))->render() !!}

@endsection
