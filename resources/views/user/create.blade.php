@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' create a new ' . str_singular(Request::segment(1))) }}</h2>
    @include('includes.page.breadcrumb')

    {!! Form::open(['url' => 'users', 'class' => 'form-horizontal']) !!}
    <p>&nbsp;</p>
    <div class="col-xs-12">
        <div class="form-group">
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
            {!! Form::label('password', 'Password: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::password('password', ['class' => 'form-control'], null) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('locations', 'Locations: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('locations', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Create User', ['class' => 'btn btn-info form-control', 'name' => 'createUserButton']) !!}
        </div>
    </div>
    {!! Form::close() !!}

@endsection
