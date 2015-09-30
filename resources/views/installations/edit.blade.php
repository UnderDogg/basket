@extends('main')

@section('content')

    <h1>Edit Installation</h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $installations->name]])

    <p>&nbsp;</p>
    {!! Form::model($installations, ['method' => 'PATCH', 'action' => ['InstallationsController@update', $installations->id], 'class' => 'form-horizontal']) !!}
    <div class="col-xs-12">

        <div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                @if($installations->active == 1)
                    {!! Form::input('checkbox', 'active', null, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small', 'value' => '1']) !!}
                @else
                    {!! Form::input('checkbox', 'active', null, ['data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('validity', 'Validity Period (in seconds)', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('validity', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('custom_logo_url', 'Custom Logo (URL)', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('custom_logo_url', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('location_instruction', 'Additional Email Instruction: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::textArea('location_instruction', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('disclosure', 'In Store Disclosure: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::textArea('disclosure', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('ext_return_url', 'Return URL', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('ext_return_url', $installations->ext_return_url, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('ext_notification_url', 'Notification URL', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('ext_notification_url', $installations->ext_notification_url, ['class' => 'form-control']) !!}
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
                validity: {
                    validators: {
                        notEmpty: {
                            message: 'The validity period cannot be empty'
                        },
                        integer: {
                            message: 'The validity period is not an integer',
                            thousandsSeparator: '',
                            decimalSeparator: '.'
                        },
                        between: {
                            min: 7200,
                            max: 604800,
                            message: 'The validity period must be between 7200 and 604800'
                        }
                    }
                },
                custom_logo_url: {
                    validators: {
                        notEmpty: {
                            message: 'The custom logo url is required'
                        },
                        uri: {
                            message: 'The custom logo url must be a valid url'
                        },
                        stringLength: {
                            max: 255,
                            message: 'The url must not be greater than 255 characters'
                        }
                    }
                },
                location_instruction: {
                    validators: {
                        notEmpty: {
                            message: 'The additional email instructions field cannot be empty'
                        }
                    }
                },
                disclosure: {
                    validators: {
                        notEmpty: {
                            message: 'The in store disclosure cannot be empty'
                        }
                    }
                }
            }
        };
    </script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
@endsection
