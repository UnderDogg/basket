@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h1>INSTALLATIONS</h1>
    @include('includes.page.breadcrumb')

    <div class="panel panel-default">

        @include('includes.form.record_counter', ['object' => $installations])

        <div class="panel-heading"><h4>Installations</h4></div>
        <table class="table table-bordered table-striped table-hover">
            {{-- TABLE HEADER WITH FILTERS --}}
            {!! Form::open(array('url' => Request::url() . '/?' . Request::server('QUERY_STRING'), 'method' => 'get',  'onsubmit'=>"return submitFilter()")) !!}
            <tr>
                <th>Name @include('includes.form.input', ['field' => 'name'])</th>
                <th>Active @include('includes.form.bool_select', ['field' => 'active', 'object' => $installations,'zero'=>'Inactive','one'=>'Active'])</th>
                <th>Linked @include('includes.form.bool_select', ['field' => 'linked', 'object' => $installations,'zero'=>'Unlinked','one'=>'Linked'])</th>
                <th>
                    <span class="pull-right">Actions</span>
                    <br><hr class="hr-tight">
                    @include('includes.form.filter_buttons')
                </th>
            </tr>
            {!! Form::close() !!}
            {{-- */$x=0;/* --}}
            @foreach($installations as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="col-sm-2 col-md-1">
                        @if( $item->active == 0 )
                            <span class="label label-danger pull-right"><i class="glyphicon glyphicon-remove"></i></span>
                        @elseif( $item->active == 1 )
                            <span class="label label-success pull-right"><i class="glyphicon glyphicon-ok"></i></span>
                        @endif
                    </td>
                    <td class="col-xs-2 col-md-1">
                        @if( $item->linked == 0 )
                            <span class="label label-danger pull-right"><i class="glyphicon glyphicon-remove"></i></span>
                        @elseif( $item->linked == 1 )
                            <span class="label label-success pull-right"><i class="glyphicon glyphicon-ok"></i></span>
                        @endif
                    </td>

                    {{-- ACTION BUTTONS --}}
                    <td class="col-xs-3 col-sm-2 col-md-2 col-lg-1 text-right">
                        @include('includes.form.record_buttons_edit_only', ['record' => $item, 'crudName' => 'installations'])
                    </td>
                </tr>
            @endforeach
            @if($x == 0) <td colspan="5"><em>0 Installations</em></td> @endif
        </table>
    </div>
    {{-- PAGINATION BUTTONS ON RENDER() --}}
    {!! $installations->appends(Request::except('page'))->render() !!}

@endsection
