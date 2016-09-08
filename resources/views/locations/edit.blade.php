@extends('main')

@section('content')

    <h1>Edit Location</h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $location->name]])

    <p>&nbsp;</p>
    {!! Form::model($location, [
        'method' => 'PATCH',
        'action' => ['LocationsController@update', $location->id],
        'class' => 'form-horizontal'
    ]) !!}
    <div class="col-xs-12">

        <div class="form-group">
            {!! Form::label('name', 'Name:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('email', 'Email:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::input('email', 'email', null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'data-fv-emailaddress' => 'true', 'data-fv-emailaddress-multiple' => 'true', 'data-fv-emailaddress-separator' => ',', 'maxlength' => 255, 'data-fv-emailaddress-message' => 'Please enter a valid email address, or multiple email addresses, separated with only a comma']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('address', 'Address:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('address', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                <label class="checkbox-inline">
                    @if($location->active == 1)
                        {!! Form::input('checkbox', 'active', 1, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @else
                        {!! Form::input('checkbox', 'active', 0, ['data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @endif
                </label>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('converted_email', 'Converted Email:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                <label class="checkbox-inline">
                    @if($location->converted_email == 1)
                        {!! Form::input('checkbox', 'converted_email', 0, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @else
                        {!! Form::input('checkbox', 'converted_email', 1, ['data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                    @endif
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-info', 'name' => 'saveChanges']) !!}
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

@section('scripts')
    <script>
        validation = {
            fields: {
                reference: {
                    validators: {
                        notEmpty: {
                            message: 'The location reference cannot be empty'
                        },
                        regexp: {
                            regexp: '^[A-Za-z0-9\-]+$',
                            message: 'The location reference can only contain letters, numbers and underscores'
                        },
                        stringLength: {
                            max: 242,
                            message: 'The location reference must not be greater than 242 characters'
                        }
                    }
                },
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
                            message: 'The email address cannot be empty'
                        },
                        emailAddress: {},
                        stringLength: {
                            max: 255,
                            message: 'The email must not be greater than 255 characters'
                        }
                    }
                },
                address: {
                    validators: {
                        notEmpty: {
                            message: 'The address cannot be empty'
                        },
                        stringLength: {
                            max: 255,
                            message: 'The address must not be greater than 255 characters'
                        }
                    }
                }
            }
        };
    </script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
@endsection
