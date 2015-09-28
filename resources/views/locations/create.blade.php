@extends('main')

@section('content')

    <h1>Create Location</h1>
    @include('includes.page.breadcrumb')

    {!! Form::open(['url' => 'locations', 'class' => 'form-horizontal']) !!}
    <p>&nbsp;</p>
    <div class="col-xs-12">
        <div class="form-group">
            {!! Form::label('reference', 'Reference: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('reference', null, ['class' => 'form-control', 'placeholder' => 'Location Reference']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('installation_id', 'Installation: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::select('installation_id', $installations, null, ['class' => 'form-control']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('active', 'Active: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::input('checkbox', 'active', 1, ['checked' => true,'data-toggle' => 'toggle', 'data-on' => '<i class="glyphicon glyphicon-ok"></i> Active', 'data-off' => '<i class="glyphicon glyphicon-remove"></i> Inactive', 'data-onstyle' => 'success', 'data-offstyle' => 'danger', 'data-size' => 'small']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('name', 'Name: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name of location']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('email', 'Email: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email Address of Location']) !!}
            </div>
        </div><div class="form-group">
            {!! Form::label('address', 'Address: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Location Address']) !!}
            </div>
        </div><div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                {!! Form::submit('Create Location', ['class' => 'btn btn-info', 'name' => 'createLocationButton']) !!}
            </div>
        </div>
    </div>
    <p>&nbsp;</p>

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
                        }
                    }
                },
                name: {
                    validators: {
                        notEmpty: {
                            message: 'The name cannot be empty'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email address cannot be empty'
                        },
                        emailAddress: {}
                    }
                },
                address: {
                    validators: {
                        notEmpty: {
                            message: 'The address cannot be empty'
                        }
                    }
                }
            }
        };
    </script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
@endsection
