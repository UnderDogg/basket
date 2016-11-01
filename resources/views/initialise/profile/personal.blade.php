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

                @if(!isset($user))
                    {!! Form::hidden('reference', $application->ext_id) !!}
                @else
                    {!! Form::hidden('user', $user) !!}
                @endif
                <h4 class="text-muted">Basic Details</h4>

                <div class="form-group">
                        {!! Form::label('title', 'Title', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <small>(Optional)</small>
                        <div class="col-sm-8">
                            <select class="form-control col-xs-12" name="title" data-fv-different="true" data-fv-different-field="marital_status">
                                <option value="">-- Please select --</option>
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
                        <input type="hidden" name="date_of_birth" />
                        <small>(Optional)</small>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-4 col-xs-4">
                                    {!! Form::selectRange('day', 1, 31, null, ['id'=> 'dob_day', 'class' => 'form-control'])  !!}
                                </div>
                                <div class="col-sm-4 col-xs-4">
                                    {!! Form::selectMonth('month', null, ['id'=> 'dob_month','class' => 'form-control']) !!}
                                </div>
                                <div class="col-sm-4 col-xs-4">
                                    {!! Form::selectYear('year', \Carbon\Carbon::now()->subyears(18)->year, \Carbon\Carbon::now()->subyears(81)->year, null, ['id'=> 'dob_year', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="dob-error col-sm-8 col-md-offset-2 col-xs-12"></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('marital_status', 'Marital Status', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <small>(Optional)</small>
                        <div class="col-sm-8">
                            <select class="form-control col-xs-12" name="marital_status" data-fv-numeric="true">
                                <option value="">-- Please select --</option>
                                @foreach ($maritalStatuses as $status)
                                    <option value="{!! $status['id']!!}">{!!$status['description'] !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('number_of_dependents', 'Dependents', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <small>(Optional)</small>
                        <div class="col-sm-8">
                            {!! Form::selectRange('number_of_dependents', 0, 10, null, ['id' => 'number_of_dependents', 'class' => 'form-control', 'data-fv-numeric' => 'true']) !!}
                        </div>
                    </div>
                    <hr />
                    <h4 class="text-muted"><abbr title="Please provide either a mobile or home phone number. If the contact information is found to be incorrect it could delay or void an application">Contact Number</abbr></h4>
                    <div class="form-group">
                        {!! Form::label('phone_mobile', 'Mobile Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></div>
                                {!! Form::text('phone_mobile', isset($phone_mobile) ? $phone_mobile : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">— Or —</div>
                    <div class="form-group">
                        {!! Form::label('phone_home', 'Home Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></div>
                                {!! Form::text('phone_home', isset($phone_home) ? $phone_home : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::token() !!}

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <div class="pull-right">
                                <a href="/" class="btn btn-default">Cancel</a>
                                @if(!isset($user))
                                    {!! Form::submit('Continue', ['class' => 'btn btn-success', 'name' => 'savePersonal']) !!}
                                @else
                                    <a class="btn btn-info" data-target="save" data-source="ajax">Save Address</a>
                                @endif
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

            var phoneValidation = {
                callback: {
                    message: 'You must enter at least one contact phone number',
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

            $('#personal').on('change', '#dob_day, #dob_month, #dob_year', function(e) {
                var y = $('#personal').find('#dob_year').val(),
                    m = $('#personal').find('#dob_month').val(),
                    d = $('#personal').find('#dob_day').val();

                $('#personal').find('[name="date_of_birth"]').val(y === '' && m === '' && d === '' ? '' : [y, m, d].join('-'));
                $('#personal').formValidation('revalidateField', 'date_of_birth');
            });

            $('#personal').formValidation({
                framework: 'bootstrap',
                fields: {
                    date_of_birth: {
                        err: '.dob-error',
                        excluded: false,
                        validators: {
                            date: {
                                format: 'YYYY-MM-DD',
                                message: 'Please fully enter the date of birth'
                            }
                        }
                    },
                    phone_home: {validators: phoneValidation},
                    phone_mobile: {validators: phoneValidation}
                }
            });

            $('#dob_day').prepend( '<option value="">-- Day --</option>');
            $('#dob_month').prepend( '<option value="">-- Month --</option>');
            $('#dob_year').prepend( '<option value="">-- Year --</option>');
            $('#dob_day :nth-child(1)').prop('selected', true);
            $('#dob_month :nth-child(1)').prop('selected', true);
            $('#dob_year :nth-child(1)').prop('selected', true);

            $("#number_of_dependents option[value='10']").html('10 +');
            $("#number_of_dependents").prepend( '<option value="">-- Please select --</option>');
            $('#number_of_dependents :nth-child(1)').prop('selected', true);
        });
    </script>
@endif
