<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingPersonal">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePersonal" aria-expanded="true" aria-controls="collapseOne">
                    Personal <span class="personal-status"></span>
                </a>
            </h4>
        </div>
        <div id="collapsePersonal" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingPersonal">
            <div class="panel-body">
                {!! Form::open(['url' => '/locations/' . $location->id . '/applications/' . $application->ext_id . '/profile', 'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'personal']) !!}
                {!! Form::hidden('reference', $application->ext_id) !!}
                    <h4 class="lead text-muted">Basic Details</h4>

                <div class="form-group">
                        {!! Form::label('title', 'Title', ['class' => 'col-sm-2 control-label text-right', 'data-fv-notempty' => 'true']) !!}
                        <small>(Optional)</small>
                        <div class="col-sm-8">
                            <select class="form-control col-xs-12" name="title">
                                <option value="">Please select&hellip;</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Miss">Miss</option>
                                <option value="Ms">Ms</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('first_name', 'First Name', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('first_name', isset($first_name) ? $first_name : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a first name', 'maxlength' => 50]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('last_name', 'Last Name', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('last_name', isset($last_name) ? $last_name : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a last name', 'maxlength' => 50]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('date_of_birth', 'Date of Birth', ['class' => 'col-sm-2 control-label']) !!}
                        <small>(Optional)</small>
                        <div class="col-sm-8">
                            {!! Form::text('date_of_birth', isset($date_of_birth) ? $date_of_birth : null, ['class' => 'form-control', 'data-fv-date' => 'true', 'data-fv-date-format' => 'YYYY-MM-DD', 'data-fv-date-message' => 'Please enter a valid date in the following format: YYYY-MM-DD', 'maxlength' => 10]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('martial_status', 'Martial Status', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <small>(Optional)</small>
                        <div class="col-sm-8">
                            <select class="form-control col-xs-12" name="martial_status">
                                <option value="">Please select&hellip;</option>
                                @foreach ($martialStatuses as $status)
                                    <option value="{!!$status['id']!!}">{!!$status['description']!!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr />
                    <h4 class="lead text-muted"><abbr title="Please provide either a mobile or home phone number. If the contact information is found to be incorrect it could delay or void an application">Contact Number</abbr></h4>
                    <div class="form-group">
                        {!! Form::label('phone_mobile', 'Mobile Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('phone_mobile', isset($phone_mobile) ? $phone_mobile : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                        </div>
                    </div>
                    <div class="form-group text-center">— Or —</div>
                    <div class="form-group">
                        {!! Form::label('phone_home', 'Home Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('phone_home', isset($phone_home) ? $phone_home : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                        </div>
                    </div>
                    {!! Form::token() !!}

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <div class="pull-right">
                                <a href="/" class="btn btn-default">Cancel</a>
                                {!! Form::submit('Continue', ['class' => 'btn btn-success', 'name' => 'savePersonal']) !!}
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@if(isset($validation) && $validation == true)
    <script>
        // Programmatic ONLY needed for "one or the other" phone numbers
        $(document).ready(function() {

            var validators = {
                callback: {
                    message: 'You must enter at least one phone number',
                    callback: function (value, validator) {
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
                    }
                }
            };

            $('#personal').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    phone_home: {validators: validators},
                    phone_mobile: {validators: validators}
                }
            });
        });
    </script>
@endif
