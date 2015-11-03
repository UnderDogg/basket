@extends('main')

@section('content')

    <h1>Edit User</h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $user->name]])

    <p>&nbsp;</p>
    {!! Form::model($user, ['method' => 'PATCH', 'action' => ['UsersController@update', $user->id], 'class' => 'form-horizontal']) !!}

    <div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>User Details</strong></div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div><div class="form-group">
                        {!! Form::label('email', 'Email: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                        </div>
                    </div><div class="form-group">
                        {!! Form::label('password', 'Password: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::password('password', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('merchant', 'Merchant: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('merchant_id', $merchants, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>User Roles</strong></div>
                <div class="form-horizontal">
                    @if($rolesApplied !== null)
                        @foreach ($rolesApplied as $location)
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-5">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox($location->name, $location->id, true) !!} {{$location->name}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if($rolesAvailable !== null)
                        @foreach ($rolesAvailable as $location)
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-5">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox($location->name, $location->id, false) !!} {{$location->name}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    <div class="form-group">
        <div style="right: 15px" class="pull-right col-sm-3 col-xs-4">
            {!! Form::submit('Save Changes', ['class' => 'btn btn-info form-control', 'name' => 'saveChanges']) !!}
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
