@extends('main')

@section('content')

    <h1>Locations
        <div class="btn-group pull-right">
            <a href="{{ Request::url() }}/create" class="btn btn-info">Add New Location</a>
        </div>
    </h1>

    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])
    <p><strong>{{ $locations->count() }}</strong> Record(s) / <strong>{{ $locations->total() }}</strong> Total</p>

    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th class="hidden-xs">Reference</th>
            <th>Name</th>
            <th class="hidden-xs">Installation</th>
            <th>Active</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th class="hidden-xs">{!! Form::text('reference', Request::only('reference')['reference'], ['class' => 'filter pull-down', 'placeholder' => 'Location Reference']) !!}</th>
            <th>{!! Form::text('name', Request::only('name')['name'], ['class' => 'filter pull-down', 'placeholder' => 'Location Name']) !!}</th>
            <th class="hidden-xs">{!! Form::select('installation_id', $installation_id, Request::only('installation_id')['installation_id'], ['class' => 'filter form-control']) !!}</th>
            <th>{!! Form::select('active', $active, Request::only('active')['active'], ['class' => 'filter form-control']) !!}</th>
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


        @forelse($locations as $item)
            <tr>
                <td class="hidden-xs">{{ $item->reference }}</td>
                <td>{{ $item->name }}</td>
                <td class="hidden-xs">@if($item->installation !== null){{ $item->installation->name }} @endif</td>
                <td>
                    @if( $item->active == 0 )
                        <span class="label label-danger pull-right"><i class="glyphicon glyphicon-remove"></i> Inactive</span>
                    @elseif( $item->active == 1 )
                        <span class="label label-success pull-right"><i class="glyphicon glyphicon-ok"></i> Active</span>
                    @endif
                </td>

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
            <tr><td colspan="5"><em>0 Locations</em></td></tr>
        @endforelse
    </table>

    {!! Form::close() !!}

    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $locations->appends(Request::except('page'))->render() !!}


@endsection
