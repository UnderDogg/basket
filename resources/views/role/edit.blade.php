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
        <div style="padding-right: 15px;" class="form-group">
            <div class="pull-right col-sm-3 col-xs-4">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}
@endsection

@section('scripts')
    <script>
        validation = {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'The name cannot be empty'
                        },
                        stringLength: {
                            max: 255,
                            message: 'The name must not be greater than 255 characters'
                        }
                    }
                },
                display_name: {
                    validators: {
                        notEmpty: {
                            message: 'The display name cannot be empty'
                        },
                        stringLength: {
                            max: 255,
                            message: 'The display name must not be greater than 255 characters'
                        }
                    }
                },
                description: {
                    validators: {
                        stringLength: {
                            max: 50000,
                            message: 'The display name must not be greater than 50000 characters'
                        }
                    }
                }
            }
        };
    </script>
@endsection
