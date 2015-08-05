@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' edit ' . str_singular(Request::segment(1))) }}</h2>
    @include('includes.page.breadcrumb', ['override2'=>$user->name])

    <p>&nbsp;</p>
    {!! Form::model($user, ['method' => 'PATCH', 'action' => ['UsersController@update', $user->id], 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">USER DETAILS</h3></div>
                <div class="panel-body">

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
                            {!! Form::password('password', ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('merchant', 'Merchant: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::select('merchant_id', $merchants, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div style="height: 100%;" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">USER ROLES</h3>
                </div>
                <div class="panel-body panel-tight-space">
                    <div class="col-xs-6">
                        <h3 class="panel-title">Applied Roles</h3>
                        <hr class="hr-tight">
                    </div>
                    <div class="col-xs-6">
                        <h3 class="panel-title">Roles Available</h3>
                        <hr class="hr-tight">
                    </div>
                </div>
                <div class="panel-body panel-tight-space" style="display: table; margin-bottom: 20px;">
                    <div style="display: table-cell; float: none;" id="permissionsAppliedHolder" class="connectedSortable col-xs-6">
                        @foreach ($rolesApplied as $location)
                            <div name="{{ $location->id }}" class="draggableItem">{{ $location->name }}</div>
                        @endforeach
                    </div>
                    <div style="display: table-cell; float: none;" id="permissionsAvailableHolder" class="connectedSortable col-xs-6">
                        @foreach ($rolesAvailable as $location)
                            <div name="{{ $location->id }}" class="draggableItem">{{ $location->name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
        </div>
    </div>

    <input id="permissionsApplied" name="rolesApplied" type="hidden" value="@foreach ($rolesApplied as $location){{ ':'.$location->id  }}@endforeach">
    <input id="permissionsAvailable" name="rolesAvailable" type="hidden" value="">
    {!! Form::close() !!}

@endsection
