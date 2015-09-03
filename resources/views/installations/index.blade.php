@extends('main')

@section('content')

    <h1>Installations</h1>
    @include('includes.page.breadcrumb', ['permission' => [0 => Auth::user()->can('merchants-view')]])
    <p><strong>{{ $installations->count() }}</strong> Record(s) / <strong>{{ $installations->total() }}</strong> Total</p>

    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th>Name</th>
            <th>Active</th>
            <th>Linked</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th>{!! Form::text('name', Request::only('name')['name'], ['class' => 'filter col-xs-12 pull-down']) !!}</th>
            <th>{!! Form::select('active', $active, Request::only('active')['active'], ['class' => 'filter form-control']) !!}</th>
            <th>{!! Form::select('linked', $linked, Request::only('linked')['linked'], ['class' => 'filter form-control']) !!}</th>
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
        @forelse($installations as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="col-sm-2 col-md-1">
                    @if( $item->active == 0 )
                        <span class="label label-danger pull-right"><i class="glyphicon glyphicon-remove"></i> Inactive</span>
                    @elseif( $item->active == 1 )
                        <span class="label label-success pull-right"><i class="glyphicon glyphicon-ok"></i> Active</span>
                    @endif
                </td>
                <td class="col-xs-2 col-md-1">
                    @if( $item->linked == 0 )
                        <span class="label label-danger pull-right"><i class="glyphicon glyphicon-remove"></i> Unlinked</span>
                    @elseif( $item->linked == 1 )
                        <span class="label label-success pull-right"><i class="glyphicon glyphicon-ok"></i> Linked</span>
                    @endif
                </td>

                {{-- ACTION BUTTONS --}}
                <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                    <div class="btn-group">
                        <a href="{{Request::URL()}}/{{$item->id}}" type="button" class="btn btn-default btn-xs"> View </a>
                        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="{{Request::URL()}}/{{$item->id}}/edit">Edit</a></li>
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
    {!! $installations->appends(Request::except('page'))->render() !!}


@endsection
