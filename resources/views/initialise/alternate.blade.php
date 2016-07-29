@extends('master')

@section('page')
<body>
@if(env('ENV_BANNER', false))
    @include('env-banner')
@endif
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="pull-left">
                    <a href="/">
                        {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    @if($location->installation->custom_logo_url)
                        {!! HTML::image($location->installation->custom_logo_url, 'logo') !!}
                    @endif
                </div>
            </div>
        </div>
        <br/>
        <h1>Additional Information</h1>
        <br/>
        <div class="row alternate-full-form">
            <div class="col-xs-12">
                {!! Form::open(['action' => ['InitialisationController@request', Request::segment(2)], 'class' => 'form-horizontal']) !!}
                <div class="form-group">
                    {!! Form::label('reference', 'Reference', ['class' => 'col-sm-2 control-label text-right']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('reference', $input['reference'], ['class' => 'form-control col-xs-12', 'maxlength' => 40]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('description', $input['description'], ['class' => 'form-control col-xs-12', 'maxlength' => 100]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('title', 'Title', ['class' => 'col-sm-2 control-label text-right']) !!}
                    <div class="col-sm-10">
                        <select class="form-control col-xs-12" name="title">
                            <option disabled selected hidden>Please select...</option>
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Miss">Miss</option>
                            <option value="Ms">Ms</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('first_name', 'First Name', ['class' => 'col-sm-2 control-label text-right']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('first_name', null, ['class' => 'form-control col-xs-12', 'maxlength' => 30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('last_name', 'Last Name', ['class' => 'col-sm-2 control-label text-right']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('last_name', null, ['class' => 'form-control col-xs-12', 'maxlength' => 30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('applicant_email', 'Email', ['class' => 'col-sm-2 control-label text-right']) !!}
                    <div class="col-sm-10">
                        {!! Form::email('applicant_email', null, ['class' => 'form-control col-xs-12', 'maxlength' => 255]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('phone_mobile', 'Mobile (Optional)', ['class' => 'col-sm-2 control-label text-right']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('phone_mobile', null, ['class' => 'form-control col-xs-12']) !!}
                    </div>
                </div>

                <!-- Previous input values -->
                @foreach($input as $key => $value)
                    @if(!is_null($value) && !in_array($value, ['reference', 'description']))
                        {!! Form::hidden($key, $value) !!}
                    @endif
                @endforeach
                {!! Form::hidden('installation', $location->installation->id) !!}

                <div class="form-group">
                    <div class="col-sm-6 col-xs-12">
                        <button type="submit" class="btn btn-success btn-lg btn-block btn-bottom-margin" name="link" value="true">Create Application Linkph</button>
                        <button type="submit" class="btn btn-success btn-lg btn-block btn-bottom-margin" name="email" value="true">Email Application Link</button>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</body>

@endsection

@section('stylesheets')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="/formvalidation/dist/js/formValidation.min.js"></script>
    <script src="/formvalidation/dist/js/framework/bootstrap.min.js"></script>
    <script>
        validation = {
            fields: {
                reference: {
                    validators: {
                        notEmpty: {
                            message: 'You must provide a reference'
                        },
                        stringLength: {
                            max: 40,
                            message: 'The reference must not be greater than 40 characters'
                        }
                    }
                },
                description: {
                    validators: {
                        notEmpty: {
                            message: 'You must provide a description'
                        },
                        stringLength: {
                            max: 100,
                            message: 'The description must not be greater than 100 characters'
                        }
                    }
                },
                title: {
                    validators: {
                        notEmpty: {
                            message: 'You must provide a valid title'
                        }
                    }
                },
                first_name: {
                    validators: {
                        notEmpty: {
                            message: 'You must provide a first name'
                        },
                        stringLength: {
                            max: 30,
                            message: 'The first name must not be greater than 30 characters'
                        }
                    }
                },
                last_name: {
                    validators: {
                        notEmpty: {
                            message: 'You must provide a last name'
                        },
                        stringLength: {
                            max: 30,
                            message: 'The last name must not be greater than 30 characters'
                        }
                    }
                },
                applicant_email: {
                    validators: {
                        notEmpty: {
                            message: 'You must provide an email address'
                        },
                        emailAddress: {},
                        stringLength: {
                            max: 255,
                            message: 'The email must not be greater than 255 characters'
                        }
                    }
                },
                phone_mobile: {
                    validators: {
                        phone: {
                            country: "GB"
                        }
                    }
                },
            }
        }
    </script>

    <script src={!! asset('/js/fv.js') !!}></script>
@endsection
