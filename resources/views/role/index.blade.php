@extends('main')

@section('content')

    <h1>Roles
        <a href="{{ url('/roles/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New Role</a>
    </h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])
    @include('includes.form.record_counter', ['object' => $roles])

    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
    <table class="table table-bordered table-striped table-hover">

        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th>ID</th>
            <th>Display Name</th>
            <th class="hidden-xs hidden-sm">Role Code</th>
            <th class="hidden-xs hidden-sm">Description</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th>@include('includes.form.input', ['field' => 'id'])</th>
            <th>@include('includes.form.input', ['field' => 'display_name'])</th>
            <th class="hidden-xs hidden-sm">@include('includes.form.input', ['field' => 'name'])</th>
            <th class="hidden-xs hidden-sm">@include('includes.form.input', ['field' => 'description'])</th>
            <th>@include('includes.form.filter_buttons')</th>
        </tr>

        {{-- TABLE BODY: WITH ACTION BUTTONS --}}

        @forelse($roles as $item)
            <tr>
                <td class="col-xs-1">{{ $item->id }}</td>
                <td>{{ $item->display_name }}</td>
                <td class="hidden-xs hidden-sm">{{ $item->name }}</td>
                <td class="hidden-xs hidden-sm">{{ str_limit($item->description, 60) }}</td>

                {{-- ACTION BUTTONS --}}
                <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                    @include('includes.form.record_actions', ['id' => $item->id,
                        'actions' => ['edit' => 'Edit', 'delete' => 'Delete']
                    ])
                </td>
            </tr>
        @empty
            <tr><td colspan="5"><em>No records found</em></td></tr>
        @endforelse

    </table>
    {!! Form::close() !!}

    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $roles->appends(Request::except('page'))->render() !!}

@endsection
