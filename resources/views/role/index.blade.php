@extends('master')

@section('content')

    <hr>
    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>Roles
        <a href="{{ url('/role/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New Role</a>
    </h1>
    @include('includes.page.breadcrumb')
    <div class="panel panel-default">

        @include('includes.form.record_counter', ['object' => $role])

        <div class="panel-heading"><h4>Application Roles</h4></div>

        <table class="table table-bordered table-striped table-hover">

            {{-- TABLE HEADER WITH FILTERS --}}
            {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
            <tr>
                <th>ID: @include('includes.form.input', ['field' => 'id'])</th>

                <th>Display Name: @include('includes.form.select', ['field' => 'display_name', 'object' => $role])</th>

                <th class="hidden-xs hidden-sm">Role Code: @include('includes.form.input', ['field' => 'name'])</th>

                <th class="hidden-xs hidden-sm">Description: @include('includes.form.input', ['field' => 'description'])</th>

                <th>
                    <span class="pull-right">Actions</span>
                    <br><hr class="hr-tight">
                    @include('includes.form.filter_buttons')
                </th>
            </tr>
            {!! Form::close() !!}

            {{-- TABLE BODY: WITH ACTION BUTTONS --}}

            {{-- */$x=0;/* --}}
            @foreach($role as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td class="col-xs-1">{{ $item->id }}</td>
                    <td>{{ $item->display_name }}</td>
                    <td class="hidden-xs hidden-sm">{{ $item->name }}</td>
                    <td class="hidden-xs hidden-sm">{{ str_limit($item->description, 60) }}</td>

                    {{-- ACTION BUTTONS --}}
                    <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                        @include('includes.form.record_buttons', ['record' => $item, 'crudName' => 'role'])
                    </td>
                </tr>
            @endforeach
            @if($x == 0) <td colspan="5"><em>Your filter returned 0 Results</em></td> @endif
        </table>
    </div>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $role->appends(Request::except('page'))->render() !!}

@endsection
