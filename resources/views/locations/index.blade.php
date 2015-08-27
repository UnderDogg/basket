@extends('main')

@section('content')

    <h1>Locations
        <div class="btn-group pull-right">
            <a href="{{ Request::url() }}/create" class="btn btn-info">Add New Location</a>
        </div>
    </h1>

    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])
    @include('includes.form.record_counter', ['object' => $locations])

    {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
    <table class="table table-bordered table-striped table-hover">
        {{-- TABLE HEADER WITH FILTERS --}}
        <tr>
            {{-- TITLES --}}
            <th class="hidden-xs hidden-sm">Reference</th>
            <th>Name</th>
            <th class="hidden-xs hidden-sm">Installation</th>
            <th>Active</th>
            <th><span class="pull-right">Actions</span></th>
        </tr>
        <tr>
            {{-- FILTERS --}}
            <th class="hidden-xs hidden-sm">{!! Form::text('reference', Request::only('reference')['reference'], ['class' => 'filter col-xs-12 pull-down', 'placeholder' => 'Location Reference']) !!}</th>
            <th>{!! Form::text('name', Request::only('name')['name'], ['class' => 'filter col-xs-12 pull-down', 'placeholder' => 'Location Name']) !!}</th>
            <th class="hidden-xs hidden-sm">@include('includes.form.associate_select', [
                'field' => 'installation_id',
                'object' => $locations,
                'associate'=>'installation',
                'associateField'=>'name',
            ])</th>
            <th>@include('includes.form.bool_select', ['field' => 'active', 'object' => $locations,'false'=>'Inactive','true'=>'Active'])</th>
            <th>@include('includes.form.filter_buttons')</th>
        </tr>


        @forelse($locations as $item)
            <tr>
                <td class="hidden-xs hidden-sm">{{ $item->reference }}</td>
                <td>{{ $item->name }}</td>
                <td class="hidden-xs hidden-sm">@if($item->installation !== null){{ $item->installation->name }} @endif</td>
                <td class="col-sm-2 col-md-1">
                    @if( $item->active == 0 )
                        <span class="label label-danger pull-right"><i class="glyphicon glyphicon-remove"></i> Inactive</span>
                    @elseif( $item->active == 1 )
                        <span class="label label-success pull-right"><i class="glyphicon glyphicon-ok"></i> Active</span>
                    @endif
                </td>

                {{-- ACTION BUTTONS --}}
                <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                    @include('includes.form.record_actions', ['id' => $item->id,
                        'actions' => ['edit' => 'Edit', 'delete' => 'Delete']
                    ])
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
