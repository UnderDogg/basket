@extends('master')

@section('content')

    <h2>{{ Str::upper(' edit ' . str_singular(Request::segment(1))) }}</h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $applications->name]])

    <p>&nbsp;</p>
    @if($applications !== null)
    {!! Form::model($applications, ['method' => 'PATCH', 'action' => ['ApplicationsController@update', $applications->id], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('user_id', 'Requester: ', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
        </div>
    </div><div class="form-group">
        {!! Form::label('installation_id', 'Installation Id: ', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('installation_id', null, ['class' => 'form-control']) !!}
        </div>
    </div><div class="form-group">
        {!! Form::label('location_id', 'Location Id: ', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('location_id', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
        </div>
    </div>
    {!! Form::close() !!}
    @endif

@endsection
