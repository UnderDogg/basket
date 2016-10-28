<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingAddress">
        <h4 class="panel-title">
            <a role="button" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseAddress" aria-expanded="false" aria-controls="collapseAddress">
                Current Address <small>(Optional)</small> <span class="address-status"></span>
                <p class="pull-right">
                    <span class="glyphicon glyphicon-chevron-right if-collapsed" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-chevron-down if-not-collapsed" aria-hidden="true"></span>
                </p>
            </a>
        </h4>
    </div>
    <div id="collapseAddress" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingAddress">
        <div class="panel-body">
            <div class="col-sm-12">
                <form class="form-horizontal" id="address" method="POST">
                    {!! Form::hidden('user', isset($user) ? $user : null) !!}
                    <div class="form-group">
                        {!! Form::label('abode', 'Abode', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('abode', isset($abode) ? $abode : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'maxlength' => 30]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('building_name', 'Building Name', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('building_name', isset($building_name) ? $building_name : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'maxlength' => 50]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('building_number', 'Building Number', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('building_number', isset($building_number) ? $building_number : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'maxlength' => 12]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('street', 'Street', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('street', isset($street) ? $street : null, ['class' => 'form-control','maxlength' => 50]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('locality', 'Locality', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('locality', isset($locality) ? $locality : null, ['class' => 'form-control col-xs-12', 'maxlength' => 50]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('town', 'Town', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('town', isset($town) ? $town : null, ['class' => 'form-control col-xs-12', 'maxlength' => 25]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('postcode', 'Postcode', ['class' => 'col-sm-2 control-label text-right']) !!}
                            <div class="col-sm-8">
                            {!! Form::text('postcode', isset($postcode) ? $postcode : null, ['class' => 'form-control col-xs-12', 'maxlength' => 8]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('moved_in', 'Moved In', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('moved_in', isset($moved_in) ? $moved_in : null, ['class' => 'form-control', 'data-fv-notempty' => 'true', 'data-fv-date' => 'true', 'data-fv-date-format' => 'YYYY-MM-DD', 'data-fv-date-message' => 'Please enter a valid date in the following format: YYYY-MM-DD', 'maxlength' => 10]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('residential_status', 'Residential Status', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            <select class="form-control col-xs-12"
                                    name="residential_status"
                                    data-fv-notempty-message="Please select a residential status"
                                    data-fv-notempty = "true">
                                <option value="">Please select&hellip;</option>
                                @foreach ($residentialStatuses as $status)
                                    <option value="{!!$status['id']!!}">{!!$status['description']!!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {!! Form::token() !!}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <a class="btn btn-info" data-target="save" data-source="ajax">Save Address</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($validation) && $validation == true)
    <script>
        $(document).ready(function() {
            $('#address').formValidation({});
        });
    </script>
@endif
