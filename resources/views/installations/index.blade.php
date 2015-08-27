@extends('main')

@section('content')

    <h1>Installations</h1>
    @include('includes.page.breadcrumb', ['permission' => [0 => Auth::user()->can('merchants-view')]])
    @include('includes.form.record_counter', ['object' => $installations])

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
            <th>@include('includes.form.bool_select', ['field' => 'active', 'object' => $installations,'false'=>'Inactive','true'=>'Active'])</th>
            <th>@include('includes.form.bool_select', ['field' => 'linked', 'object' => $installations,'false'=>'Unlinked','true'=>'Linked'])</th>
            <th>@include('includes.form.filter_buttons')</th>
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
                    @include('includes.form.record_actions', ['id' => $item->id,
                        'actions' => ['edit' => 'Edit']
                    ])
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
