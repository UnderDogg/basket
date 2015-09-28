@extends('main')

@section('content')

    <h1>Edit Role</h1>
    @include('includes.page.breadcrumb', ['over' => [1 => $role->display_name]])

    {!! Form::model($role, ['method' => 'PATCH', 'action' => ['RolesController@update', $role->id], 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Role Details</strong></div>
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
                <div class="panel-heading"><strong>Role Permissions</strong></div>
                    <div class="form-horizontal">
                        @foreach($role->permissions as $permission)
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-5">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox($permission->display_name, $permission->id, true) !!} {{$permission->display_name}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @foreach($permissionsAvailable as $permission)
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-5">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox($permission->display_name, $permission->id, false) !!} {{$permission->display_name}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="padding-right: 15px;" class="form-group">
        <div class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
        </div>
    </div>

    <input id="permissionsApplied" name="permissionsApplied" type="hidden" value="@foreach ($role->permissions as $permission){{ ':'.$permission->id  }}@endforeach">
    <input id="permissionsAvailable" name="permissionsAvailable" type="hidden" value="">
    {!! Form::close() !!}
@endsection
