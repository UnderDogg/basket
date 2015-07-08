@extends('master')

@section('content')

    <hr/>
    @if( $role->message !== null )
        <div id="actionMessage" class="col-xs-12" hidden="hidden">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Success</strong> {{ $role->message }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div id="actionMessage" class="col-xs-12" hidden="hidden">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </div>
        </div>
    @endif

    <div class="col-xs-12">
        <h2>
            <a style="margin-bottom: 7px;" href="{{ '/' . Request::segment(1) }}" class="btn btn-info btn-xs" role="button">Back</a>
            {{ Str::upper(' edit ' . Request::segment(1)) }}
        </h2>
        <hr/>
    </div>

    {!! Form::model($role, ['method' => 'PATCH', 'action' => ['RoleController@update', $role->id], 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">ROLE DETAILS</h3></div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('display_name', 'Display Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">ROLE PERMISSIONS</h3>
                </div>
                <div class="panel-body panel-tight-space">
                    <div style="padding-right:0px;" class="col-xs-6">
                        <div class="panel rolePanel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Applied Permissions</h3>
                                <hr class="hr-tight">
                            </div>
                            <div class="panel-body panel-tight-space">
                                <div style="height: 365px;" id="permissionsAppliedHolder" class="connectedSortable col-xs-12">
                                    @foreach ($role->permissions as $permission)
                                        <div name="{{ $permission->id }}" class="draggableItem">{{ $permission->display_name }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding-left:0px;" class="col-xs-6">
                        <div class="panel rolePanel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Permissions Available</h3>
                                <hr class="hr-tight">
                            </div>
                            <div class="panel-body panel-tight-space">
                                <div style="height: 365px;" id="permissionsAvailableHolder" class="connectedSortable col-xs-12">
                                    @foreach ($role->permissionsAvailable as $permission)
                                        <div name="{{ $permission->id }}" class="draggableItem">{{ $permission->display_name }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control']) !!}
        </div>
    </div>

    <input id="permissionsApplied" name="permissionsApplied" type="hidden" value="@foreach ($role->permissions as $permission){{ ':'.$permission->id  }}@endforeach">
    <input id="permissionsAvailable" name="permissionsAvailable" type="hidden" value="">
    {!! Form::close() !!}

@endsection
