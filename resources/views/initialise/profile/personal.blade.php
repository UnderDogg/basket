<div class="col-sm-offset-2">
    <h2>Personal <span class="personal-status"></span></h2>
    <hr/>
</div>
<form class="form-horizontal" id="form-personal" method="POST">
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
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-8">
            <button class="btn btn-info" name="savePersonal" id="savePersonal">Save Personal Information</button>
        </div>
    </div>
</form>

@if(isset($validation) && $validation == true)
    <script>
        // Programmatic ONLY needed for "one or the other" phone numbers
        $(document).ready(function() {
            var personal = '#form-personal';
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

            $('#savePersonal').on('click', function (e) {
                e.preventDefault();
                var formValidation = $(personal).data('formValidation');
                formValidation.validate();
                if (formValidation.isValidContainer(personal)) {
                    var installation = $('input[name="installation"]').val();
                    var formData = $(personal).serializeArray();
                    $.ajax({
                        url: '/ajax/installations/' + installation + '/profile/personal',
                        type: 'POST',
                        data: formData,
                        dataType: 'JSON',
                        encode: true,
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function () {
                            hideLoading();
                            // Disable the form from being submitted again
                            formValidation.disableSubmitButtons(true);
                            updateFormStatus('personal', true);
                        },
                        error: function (response) {
                            hideLoading();
                            try {
                                var errorText = JSON.parse(response.responseText).error;
                            } catch (e) {
                                console.log('Error Encountered: ' + e);
                                errorText = 'There was a problem with the API';
                            }
                            // Enable the form to be submitted again
                            formValidation.disableSubmitButtons(false);
                            updateFormStatus('personal', false);
                            swal({
                                title: 'An Error Occurred!',
                                text: 'We were unable to save the information provided.' +
                                        '</br></br>[' + errorText[0].toUpperCase() + errorText.slice(1) + ']',
                                type: 'error',
                                html: true,
                                showCancelButton: false,
                                confirmButtonColor: '#DD6B55',
                                confirmButtonText: 'Close',
                                closeOnConfirm: false
                            });
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });

                    return false;
                }

                return false;
            });

            function updateFormStatus(formType, success) {
                var glyph = 'remove';
                var colour = 'danger';
                if (success) {
                    glyph = 'ok';
                    colour = 'success'
                }
                $('.' + formType + '-status').html('<small class="text-' + colour + '"><span class="glyphicon glyphicon-' + glyph + '" aria-hidden="true"></span></small>');
            }

            function showLoading() {
                $('.loading').show();
            }

            function hideLoading() {
                $('.loading').hide();
            }
        });
    </script>
@endif
