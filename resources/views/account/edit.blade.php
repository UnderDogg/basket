@extends('main')

@section('content')

    <h1>Edit account details</h1>
    @include('includes.page.breadcrumb')
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">User Details</h3></div>
        <div class="panel-body">
            <div class="row">
                {!! Form::model($user, array('method' => 'post', 'class' => 'form-horizontal')) !!}
                <div class="col-xs-2 col-sm-4 col-md-2 col-lg-2">
                    <div class="thumbnail">
                        <img src="{{ '//www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?size=200' }}" alt="...">
                    </div>
                </div>
                <div class="col-xs-10 col-sm-8 col-md-10 col-lg-10">
                    &nbsp;
                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', 'Email: ', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            {!! Form::submit('Update details', ['class' => 'btn btn-info']) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="row">
                <div class="col-xs-10 col-sm-8 col-md-10 col-lg-10 pull-right">
                    {!! Form::open(array('url' => Request::url() . '/password', 'method' => 'post', 'class' => 'form-horizontal')) !!}
                    <div class="form-group">
                        {!! Form::label('old_password', 'Old password:', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::password('old_password', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('new_password', 'New password:', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::password('new_password', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('new_password_confirmation', 'Confirm password:', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::password('new_password_confirmation', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            {!! Form::submit('Change password', ['class' => 'btn btn-info']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        </div>
    </div>
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
                            message: 'The ip address must not be greater than 255 characters'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email address cannot be empty'
                        },
                        emailAddress: {
                            message: 'The email must be valid'
                        },
                        stringLength: {
                            max: 255,
                            message: 'The email address must not be greater than 255 characters'
                        }
                    }
                },
                old_password: {
                    validators: {
                        notEmpty: {
                            message: 'The old password cannot be empty'
                        },
                        stringLength: {
                            max:255,
                            message: 'The old password must not be greater than 255 characters'
                        }
                    }
                },
                new_password: {
                    validators: {
                        notEmpty: {
                            message: 'The new password cannot be empty'
                        },
                        different: {
                            field: 'old_password',
                            message: 'The new password must be different from the old password'
                        },
                        stringLength: {
                            max:255,
                            message: 'The new password must not be greater than 255 characters'
                        }
                    }
                },
                new_password_confirmation: {
                    validators: {
                        notEmpty: {
                            message: 'The confirmed password cannot be empty'
                        },
                        identical: {
                            field: 'new_password',
                            message: 'The password and its confirm are not the same'
                        },
                        stringLength: {
                            max:255,
                            message: 'The confirmed password must not be greater than 255 characters'
                        }
                    }
                }
            }
        };
    </script>
@endsection
