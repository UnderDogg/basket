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
            <div class="col-sm-8 col-sm-offset-2">
                <h2>New Application Link</h2>
                <div class="panel panel-default">
                    <div class="panel-heading"><p class="panel-title">Basic Details</p></div>
                    <div class="panel-body">
                        <div class="col-xs-12">
                            {!! Form::open(['action' => ['InitialisationController@request', $location->id], 'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'order']) !!}
                            <h4 class="text-muted">Order Details</h4>
                            <div class="form-group">
                                {!! Form::label('reference', 'Your Reference', ['class' => 'col-sm-2 control-label text-right']) !!}
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></div>
                                        {!! Form::text('reference',null, ['class' => 'form-control col-xs-12', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a reference', 'maxlength' => 255, 'data-fv-stringlength' => true,'data-fv-stringlength-min' => 6]) !!}
                                        <span class="input-group-btn"><a id="regen_btn" class="btn btn-info"><abbr title="Click to generate order reference"><span class="glyphicon glyphicon-refresh"></span></abbr></a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('description', 'Order Description', ['class' => 'col-sm-2 control-label text-right']) !!}
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></div>
                                        {!! Form::text('description', 'Goods & Services', ['class' => 'form-control col-xs-12', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a description for this order', 'maxlength' => 255, 'data-fv-stringlength' => true,'data-fv-stringlength-min' => 6]) !!}
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <h4 class="text-muted">Customer Details</h4>
                            <div class="form-group">
                                {!! Form::label('email', 'Customer Email', ['class' => 'col-sm-2 control-label text-right']) !!}
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></div>
                                        {!! Form::email('email', null, ['class' => 'form-control col-xs-12', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter an email address', 'data-fv-emailaddress' => 'true', 'maxlength' => 255]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('first_name', 'First Name', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('first_name', isset($application->ext_customer_first_name) ? $application->ext_customer_first_name : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a first name', 'maxlength' => 50]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('last_name', 'Last Name', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('last_name', isset($application->ext_customer_last_name) ? $application->ext_customer_last_name : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a last name', 'maxlength' => 50]) !!}
                                </div>
                            </div>
                            <hr />
                            <h4 class="text-muted"><abbr title="Please provide either a mobile or home phone number. If the contact information is found to be incorrect it could delay or void an application">Contact Number</abbr></h4>
                            <div class="form-group">
                                {!! Form::label('phone_mobile', 'Mobile Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></div>
                                        {!! Form::text('phone_mobile', isset($application->ext_customer_phone_mobile) ? $application->ext_customer_phone_mobile : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">— Or —</div>
                            <div class="form-group">
                                {!! Form::label('phone_home', 'Home Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></div>
                                        {!! Form::text('phone_home', isset($application->ext_customer_phone_home) ? $application->ext_customer_phone_home : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- Previous input values -->
                            @foreach($input as $key => $value)
                                @if(!is_null($value) && !in_array($key, ['reference', 'description']))
                                    {!! Form::hidden($key, $value) !!}
                                @endif
                            @endforeach
                            {!! Form::hidden('installation', $location->installation->id) !!}
                            {!! Form::hidden('location_reference', $location->reference, ['id' => 'location_reference']) !!}

                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12 col-sm-offset-2">
                                    <div class="pull-right">
                                        <a href="/" class="btn btn-default btn-bottom-margin">Cancel</a>
                                        {!! Form::submit('Create', ['class' => 'btn btn-success btn-bottom-margin', 'name' => 'assisted', 'value' => true]) !!}
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

@endsection

@section('stylesheets')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="{!! Bust::cache('/formvalidation/dist/js/formValidation.min.js') !!}"></script>
    <script src="{!! Bust::cache('/formvalidation/dist/js/framework/bootstrap.min.js') !!}"></script>

    <script>
        $(document).ready(function() {
            var atLeastOnePhoneNumberIsEntered = function (validator) {
                var isEmpty = true;
                var mobile = validator.getFieldElements('phone_mobile');
                if (mobile.eq(0).val() !== '') {
                    isEmpty = false;
                }
                var home = validator.getFieldElements('phone_home');
                if (home.eq(0).val() !== '') {
                    isEmpty = false;
                }

                if (!isEmpty) {
                    validator.updateStatus('phone_mobile', validator.STATUS_VALID, 'callback');
                    validator.updateStatus('phone_home', validator.STATUS_VALID, 'callback');
                    return true;
                }
                return false;
            };

            var phoneValidation = {
                regexp: {
                    message: 'A mobile number must be at least 11 characters long',
                    regexp: /(?:(?=^07.*$)^\d{11}$|(?!^07.*$)^.*$)/
                },
                callback: {
                    message: 'You must enter at least one contact phone number',
                    callback: function (value, validator) {
                        return  atLeastOnePhoneNumberIsEntered(validator)
                    }
                }
            };

            $('#order').formValidation({
                framework: 'bootstrap',
                fields: {
                    phone_home: {validators: phoneValidation},
                    phone_mobile: {validators: phoneValidation}
                }
            });

            $('#regen_btn').on('click', function() {
                populateGuid();
                $('#order').data('formValidation').revalidateField('reference');
            });

            function populateGuid()
            {
                var reference = $('#location_reference').val() + '-' + guid();
                $('#reference').val(reference);
                $('#reference').text(reference);
            }

            function guid() {
                function s4() {
                    return Math.floor((1 + Math.random()) * 0x10000)
                            .toString(16)
                            .substring(1);
                }
                return s4() + s4() + s4();
            }
        });
    </script>
@endsection
