<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingEmployment">
            <h4 class="panel-title">
                <a role="button" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEmployment" class="collapsed">
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
                                <select class="form-control col-xs-12"
                                        name="employment_status"
                                        data-fv-notempty-message="Please select an employment status">
                                    <option value="">Please select&hellip;</option>
                                    @foreach ($employmentStatuses as $status)
                                    <option value="{!!$status['id']!!}">{!!$status['description']!!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('employment_start', 'Employment Start Date', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::text('employment_start', isset($employment_start) ? $employment_start : null, ['class' => 'form-control', 'data-fv-date' => 'true', 'data-fv-date-format' => 'YYYY-MM-DD', 'data-fv-date-message' => 'Please enter a valid date in the following format: YYYY-MM-DD', 'maxlength' => 10]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('phone_employer', 'Employer\'s Phone Number' , ['class' => 'col-sm-2 control-label text-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::text('phone_employer', isset($phone_employer) ? $phone_employer : null, ['class' => 'form-control col-xs-12', 'data-fv-phone' => 'true', 'data-fv-phone-country' => 'GB', 'maxlength' => 11]) !!}
                            </div>
                        </div>
                        {!! Form::token() !!}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-info" id="employmentBtn" data-target="save" data-source="ajax">Save Employment</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($validation) && $validation == true)
    <script>
        $(document).ready(function() {
            $('#employment').formValidation({ });

            $(function() {
                $('#employment :input').not(':hidden').on('input', function() {
                    var completed = $('#employment :input').not(':hidden').filter(function() { return $(this).val(); }).length > 0;
                    $('#employmentBtn').attr('disabled', !completed);
                    if (completed) {
                        $('#employmentBtn').bind('click', function(e){
                            e.preventDefault();
                        });
                    } else {
                        $('#employmentBtn').unbind('click');
                    }
                });
            });
        });
    </script>
@endif
