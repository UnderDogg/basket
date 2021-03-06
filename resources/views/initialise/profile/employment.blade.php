<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingEmployment">
        <h4 class="panel-title">
            <a role="button" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEmployment" aria-expanded="false" aria-controls="collapseEmployment">
                Employment <small>(Optional)</small> <span class="employment-status"></span>
                <p class="pull-right">
                    <span class="glyphicon glyphicon-chevron-right if-collapsed" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-chevron-down if-not-collapsed" aria-hidden="true"></span>
                </p>
            </a>
        </h4>
    </div>
    <div id="collapseEmployment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEmployment">
        <div class="panel-body">
            <div class="col-sm-12">
                <form class="form-horizontal" id="employment" method="POST">
                    {!! Form::hidden('user', isset($user) ? $user : null) !!}
                    <div class="form-group">
                        {!! Form::label('employment_status', 'Employment Status', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            <select class="form-control" name="employment_status" data-fv-numeric="true" data-fv-notempty="true" data-fv-notempty-message="Please select an employment status">
                                <option value="">-- Please select --</option>
                                @foreach ($employmentStatuses as $status)
                                <option value="{!!$status['id']!!}">{!!$status['description']!!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('employment_start', 'Employment Start Date', ['class' => 'col-sm-2 control-label']) !!}
                        {!! Form::hidden('employment_start') !!}
                        <div class="col-sm-8 employment-start-error">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6">
                                    {!! Form::selectMonth('month', null, ['id'=> 'employment_start_month','class' => 'form-control']) !!}
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    {!! Form::selectYear('year', \Carbon\Carbon::now()->year, \Carbon\Carbon::now()->subyears(40)->year, null, ['id'=> 'employment_start_year', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone_employer', 'Employer\'s Phone Number' , ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></div>
                                {!! Form::text('phone_employer', isset($phone_employer) ? $phone_employer : null, ['class' => 'form-control', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::token() !!}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <a class="btn btn-info btn-block" id="employmentBtn" data-target="save" data-source="ajax">Save Employment</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
