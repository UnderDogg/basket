<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingPersonal">
        <h4 class="panel-title">
            @if(isset($user))
            <a role="button" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePersonal" @if(!isset($user))aria-expanded="true" @endif aria-controls="collapseOne">
            @endif
                Personal <span class="personal-status">@if(isset($user))<small class="text-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></small>@endif</span>
                @if(isset($user))
                <p class="pull-right">
                    <span class="glyphicon glyphicon-chevron-right if-collapsed" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-chevron-down if-not-collapsed" aria-hidden="true"></span>
                </p>
            </a>
            @endif
        </h4>
    </div>
    <div id="collapsePersonal" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingPersonal">
        <div class="panel-body">
            {!! Form::open(['url' => '/locations/' . $location->id . '/applications/' . $application->id . '/profile', 'class' => 'form-horizontal', 'method' => 'POST', 'id' => 'personal']) !!}

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
                        {!! Form::select('title', ['' => '-- Please select --', 'Mr' => 'Mr', 'Mrs' => 'Mrs', 'Miss' => 'Miss', 'Ms' => 'Ms'], isset($application->ext_customer_title) ? $application->ext_customer_title : null, ['class' => 'form-control', 'data-fv-different' => 'true', 'data-fv-different-field' => 'marital-status']) !!}
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
                <div class="form-group">
                    {!! Form::label('date_of_birth', 'Date of Birth', ['class' => 'col-sm-2 control-label']) !!}
                    <input type="hidden" name="date_of_birth" />
                    <small>(Optional)</small>
                    <div class="col-sm-8 dob-error">
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
                </div>
                <div class="form-group">
                    {!! Form::label('marital_status', 'Marital Status', ['class' => 'col-sm-2 control-label text-right']) !!}
                    <small>(Optional)</small>
                    <div class="col-sm-8">
                        <select class="form-control" name="marital_status" data-fv-numeric="true">
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
                            {!! Form::text('phone_mobile', isset($application->ext_customer_phone_mobile) ? $application->ext_customer_phone_mobile : null, ['class' => 'form-control', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group text-center">— Or —</div>
                <div class="form-group">
                    {!! Form::label('phone_home', 'Home Phone', ['class' => 'col-sm-2 control-label text-right']) !!}
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></div>
                            {!! Form::text('phone_home', isset($application->ext_customer_phone_home) ? $application->ext_customer_phone_home : null, ['class' => 'form-control', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                        </div>
                    </div>
                </div>
                {!! Form::token() !!}

                @if(!isset($user))
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <div class="pull-right">
                                <a href="/" class="btn btn-default">Cancel</a>
                                {!! Form::submit('Continue', ['class' => 'btn btn-success', 'name' => 'savePersonal']) !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <a class="btn btn-info btn-block" data-target="save" data-source="ajax">Save Personal</a>
                        </div>
                    </div>
                @endif
            {!! Form::close() !!}
        </div>
    </div>
</div>
