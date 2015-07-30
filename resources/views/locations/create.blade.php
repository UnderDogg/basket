@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' create a new ' . str_singular(Request::segment(1))) }}</h2>
    @include('includes.page.breadcrumb')

    {!! Form::open(['url' => 'locations', 'class' => 'form-horizontal']) !!}
    <p>&nbsp;</p>
    <div class="col-xs-12">
        <div class="form-group">
            {!! Form::label('reference', 'Reference: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('reference', null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('installation_id', 'Installation ID: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('installation_id', null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('active', 'Active: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('active', null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('email', 'Email: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('address', 'Address: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('address', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Create Location', ['class' => 'btn btn-info form-control', 'name' => 'createLocationButton']) !!}
        </div>
    </div>
    {!! Form::close() !!}

@endsection