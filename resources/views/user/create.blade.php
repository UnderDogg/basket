@extends('main')

@section('content')

    <h1>Create a new User</h1>
    @include('includes.page.breadcrumb')

    {!! Form::open(['url' => 'users', 'class' => 'form-horizontal']) !!}
    <p>&nbsp;</p>

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">USER DETAILS</h3></div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name of user']) !!}
                        </div>
                    </div><div class="form-group">
                        {!! Form::label('email', 'Email: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'User&#39;s email address']) !!}
                        </div>
                    </div><div class="form-group">
                        {!! Form::label('password', 'Password: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password'], null) !!}
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
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Role Permissions</strong></div>
                <div class="form-horizontal">
                    @foreach($rolesApplied as $role)
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-5">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox($role->name, $role->id, false) !!} {{$role->name}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @foreach($rolesAvailable as $role)
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-5">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox($role->name, $role->id, false) !!} {{$role->name}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Create User', ['class' => 'btn btn-info form-control', 'name' => 'createUserButton']) !!}
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
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email cannot be empty'
                        },
                        emailAddress: {
                            message: 'The email must be valid'
                        },
                        stringLength: {
                            max: 255,
                            message: 'The email must not be greater than 255 characters'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: 'The password cannot be empty'
                        },
                        stringLength: {
                            max:255,
                            message: 'the password must not be greater than 255 characters'
                        }
                    }
                }
            }
        };
    </script>
@endsection
