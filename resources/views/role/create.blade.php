@extends('master')

@section('content')

    <h1>Create New Role</h1>
    <hr/>

    {!! Form::open(['url' => 'role', 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">ROLE DETAILS</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('display_name', 'Display Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
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
                                <div style="height: 350px;" id="permissionsAppliedHolder" class="connectedSortable col-xs-12">
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
                                <div style="height: 350px;" id="permissionsAvailableHolder" class="connectedSortable col-xs-12">
                                    @foreach ($permissionsAvailable as $permission)
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
            {!! Form::submit('Update', ['class' => 'btn btn-info form-control']) !!}
        </div>
    </div>

    <input id="permissionsAvailable" name="permissionsAvailable" type="hidden" value="@foreach ($permissionsAvailable as $permission){{ ':'.$permission->id  }}@endforeach">
    <input id="permissionsApplied" name="permissionsApplied" type="hidden" value="">
    {!! Form::close() !!}

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

@endsection
