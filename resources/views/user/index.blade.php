@extends('master')

@section('content')

    <h1>Users
        <a href="{{ url('/users/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New User</a>
    </h1>

    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])
    @include('includes.form.record_counter', ['object' => $users])

    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Merchant</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            <th>{!! Form::text('name', Request::only('name')['name'], ['class' => 'filter col-xs-12 pull-down', 'placeholder' => 'Name of user']) !!}</th>
            <th>{!! Form::text('email', Request::only('email')['email'], ['class' => 'filter col-xs-12 pull-down', 'placeholder' => 'User&#39;s email']) !!}</th>
            <th>@include('includes.form.associate_select', [
                'field' => 'merchant_id',
                'object' => $users,
                'associate'=>'merchant',
                'associateField'=>'name',
            ])</th>
            <th>@include('includes.form.filter_buttons')</th>
        </tr>
        @forelse($users as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->merchant !== null?$item->merchant->name: '' }}</td>
                {{-- ACTION BUTTONS --}}
                <td class="text-right">
                    @include('includes.form.record_actions', ['id' => $item->id,
                        'actions' => ['edit' => 'Edit', 'locations' => 'Locations', 'delete' => 'Delete']
                    ])
                </td>
            </tr>
        @empty
            <tr><td colspan="5"><em>No records found</em></td></tr>
        @endforelse
    </table>
    {!! Form::close() !!}

    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $users->appends(Request::except('page'))->render() !!}

@endsection
