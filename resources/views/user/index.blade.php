@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>USERS
        <a href="{{ url('/user/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New User</a>
    </h1>
    @include('includes.page.breadcrumb')

    <div class="panel panel-default">

        @include('includes.form.record_counter', ['object' => $user])

        <div class="panel-heading"><h4>Users</h4></div>
        <table class="table table-bordered table-striped table-hover">
            {{-- TABLE HEADER WITH FILTERS --}}
            {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
            <tr>
                <th>Name @include('includes.form.input', ['field' => 'name'])</th>
                <th>Email @include('includes.form.input', ['field' => 'email'])</th>

                <th class="hidden-xs hidden-sm">Merchant @include('includes.form.associate_select', [
                    'field' => 'merchant_id',
                    'object' => $user,
                    'associate'=>'merchant',
                    'associateField'=>'name',
                ])</th>
                <th>
                    <span class="pull-right">Actions</span>
                    <br><hr class="hr-tight">
                    @include('includes.form.filter_buttons')
                </th>
            </tr>
            {!! Form::close() !!}
            {{-- */$x=0;/* --}}
            @foreach($user as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="hidden-xs hidden-sm">{{ $item->email }}</td>
                    <td class="hidden-xs hidden-sm">{{ $item->merchant->name }}</td>
                    {{-- ACTION BUTTONS --}}
                    <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                        @include('includes.form.record_actions', ['id' => $item->id,
                            'actions' => ['edit' => 'Edit', 'delete' => 'Delete']
                        ])
                    </td>
                </tr>
            @endforeach
            @if($x == 0) <td colspan="5"><em>0 Users</em></td> @endif
        </table>
    </div>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $user->appends(Request::except('page'))->render() !!}

@endsection
