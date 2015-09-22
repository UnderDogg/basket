@extends('main')

@section('content')

    <h1>Users
        <a href="{{ url('/users/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New User</a>
    </h1>

    @include('includes.page.breadcrumb')
    <p><strong>{{ $users->count() }}</strong> Record(s) / <strong>{{ $users->total() }}</strong> Total</p>


    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            <th>Name</th>
            <th class="hidden-xs visible-sm">Email</th>
            <th>Merchant</th>
            <th class="col-xs-4 col-sm-2"><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            <th>{!! Form::text('name', Request::only('name')['name'], ['class' => 'filter col-xs-12 pull-down', 'placeholder' => 'Name of user']) !!}</th>
            <th class="hidden-xs visible-sm">{!! Form::text('email', Request::only('email')['email'], ['class' => 'filter col-xs-12 pull-down', 'placeholder' => 'User&#39;s email']) !!}</th>
            <th>{!! Form::select('merchant_id', $merchant_id, Request::only('merchant_id')['merchant_id'], ['class' => 'filter form-control']) !!}</th>
            <th class="col-xs-4 col-sm-2">
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
        @forelse($users as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="hidden-xs visible-sm">{{ $item->email }}</td>
                <td>{{ $item->merchant !== null?$item->merchant->name: '' }}</td>
                {{-- ACTION BUTTONS --}}
                <td class="col-xs-4 col-sm-2 text-right">
                    <div class="btn-group">
                        <a href="{{Request::URL()}}/{{$item->id}}" type="button" class="btn btn-default btn-xs"> View </a>
                        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="{{Request::URL()}}/{{$item->id}}/edit">Edit</a></li>
                            <li><a href="{{Request::URL()}}/{{$item->id}}/locations">Locations</a></li>
                                <li role="separator" class="divider"></li>
                            <li><a href="{{Request::URL()}}/{{$item->id}}/delete">Delete</a></li>
                        </ul>
                    </div>
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
