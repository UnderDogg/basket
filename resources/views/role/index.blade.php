@extends('main')

@section('content')

    <h1>Roles
        <a href="{{ url('/roles/create') }}" name="addNewButton" class="btn btn-info pull-right">Add New Role</a>
    </h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])
    <p><strong>{{ $roles->count() }}</strong> Record(s) / <strong>{{ $roles->total() }}</strong> Total</p>


    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
    <table class="table table-bordered table-striped table-hover">

        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th class="hidden-xs">ID</th>
            <th>Display Name</th>
            <th>Role Code</th>
            <th class="hidden-xs hidden-sm">Description</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th class="hidden-xs">{!! Form::text('id', Request::only('id')['id'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th>{!! Form::text('display_name', Request::only('display_name')['display_name'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th>{!! Form::text('name', Request::only('name')['name'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th class="hidden-xs hidden-sm">{!! Form::text('description', Request::only('description')['description'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th>
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

        {{-- TABLE BODY: WITH ACTION BUTTONS --}}

        @forelse($roles as $item)
            <tr>
                <td class="hidden-xs">{{ $item->id }}</td>
                <td>{{ $item->display_name }}</td>
                <td>{{ $item->name }}</td>
                <td class="hidden-xs hidden-sm">{{ str_limit($item->description, 60) }}</td>

                {{-- ACTION BUTTONS --}}
                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-right">
                    <div class="btn-group">
                        <a href="{{Request::URL()}}/{{$item->id}}" type="button" class="btn btn-default btn-xs"> View </a>
                        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="{{Request::URL()}}/{{$item->id}}/edit">Edit</a></li>
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
    {!! $roles->appends(Request::except('page'))->render() !!}

@endsection
