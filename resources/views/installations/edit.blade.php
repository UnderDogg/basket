@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' edit ' . str_singular(Request::segment(1))) }}</h2>
    @include('includes.page.breadcrumb', ['override2'=>$installations->name])

    <p>&nbsp;</p>

    {!! Form::model($installations, ['method' => 'PATCH', 'action' => ['InstallationsController@update', $installations->id], 'class' => 'form-horizontal']) !!}
    <div class="col-xs-12">
        <div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
        </div>
    </div>
    {!! Form::close() !!}

@endsection
