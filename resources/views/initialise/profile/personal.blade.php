<div class="col-sm-offset-2 col-sm-8">
    <h2>Personal <span class="personal-status"></span></h2>
    <hr/>
</div>
<div class="col-sm-12">
    <form class="form-horizontal" id="personal" method="POST">
        {!! Form::hidden('reference', isset($reference) ? $reference : null) !!}
        <div class="form-group">
            {!! Form::label('title', 'Title', ['class' => 'col-sm-2 control-label text-right']) !!}
            <div class="col-sm-8">
                <select class="form-control col-xs-12" name="title" data-fv-notempty="true" data-fv-notempty-message="Please select a title">
                    <option disabled selected hidden>Please select...</option>
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
                {!! Form::text('first_name', isset($first_name) ? $first_name : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'maxlength' => 50]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('last_name', 'Last Name', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('last_name', isset($last_name) ? $last_name : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'maxlength' => 50]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('date_of_birth', 'Date of Birth', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-8">
                {!! Form::text('date_of_birth', isset($date_of_birth) ? $date_of_birth : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'data-fv-date' => 'true', 'data-fv-date-format' => 'YYYY-MM-DD', 'data-fv-date-message' => 'Please enter a valid date in the following format: YYYY-MM-DD', 'maxlength' => 10]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('phone_mobile', 'Mobile Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
            <div class="col-sm-8">
                {!! Form::text('phone_mobile', isset($phone_mobile) ? $phone_mobile : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('phone_home', 'Home Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
            <div class="col-sm-8">
                {!! Form::text('phone_home', isset($phone_home) ? $phone_home : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
            </div>
        </div>
        {!! Form::token() !!}
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <a class="btn btn-info" data-target="save" data-source="ajax">Save Personal Information</a>
            </div>
        </div>
    </form>
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

            $(personal).formValidation({
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
