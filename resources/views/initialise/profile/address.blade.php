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
                <link rel="stylesheet" type="text/css" href="https://services.postcodeanywhere.co.uk/css/captureplus-2.30.min.css?key=kh79-rj31-an17-mu82" />
                <script type="text/javascript" src="https://services.postcodeanywhere.co.uk/js/captureplus-2.30.min.js?key=kh79-rj31-an17-mu82"></script>
                <form class="form-horizontal" id="address" method="POST">
                    {!! Form::hidden('user', isset($user) ? $user : null) !!}

                    <div class="form-group">
                        <p>Search for an address by typing your postcode in the field below</p>
                        {!! Form::label('postcode', 'Postcode', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('postcode', isset($postcode) ? $postcode : null, ['class' => 'form-control col-xs-12', 'maxlength' => 8, 'placeholder' => 'Start typing your postcode here']) !!}
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        {!! Form::label('abode', 'Flat / Unit Number', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('abode', isset($abode) ? $abode : null, ['class' => 'form-control', 'maxlength' => 30]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('building_name', 'Building Name', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('building_name', isset($building_name) ? $building_name : null, ['class' => 'form-control', 'maxlength' => 50]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('building_number', 'Building Number', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('building_number', isset($building_number) ? $building_number : null, ['class' => 'form-control', 'maxlength' => 12]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('street', 'Street', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('street', isset($street) ? $street : null, ['class' => 'form-control', 'maxlength' => 50, 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a street']) !!}
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
                            {!! Form::text('town', isset($town) ? $town : null, ['class' => 'form-control col-xs-12', 'maxlength' => 25, 'data-fv-notempty' => 'true', 'data-fv-notempty-message' => 'Please enter a town']) !!}
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        {!! Form::label('moved_in', 'Moved In', ['class' => 'col-sm-2 control-label']) !!}
                        <input type="hidden" name="moved_in" />
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-6 col-xs-6">
                                    {!! Form::selectMonth('month', null, ['id'=> 'moved_in_month','class' => 'form-control']) !!}
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    {!! Form::selectYear('year', \Carbon\Carbon::now()->year, \Carbon\Carbon::now()->subyears(30)->year, null, ['id'=> 'moved_in_year', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="moved-in-error col-sm-8 col-md-offset-2 col-xs-12"></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('residential_status', 'Residential Status', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            <select class="form-control col-xs-12" name="residential_status" data-fv-numeric="true" data-fv-notempty = "true" data-fv-notempty-message = "Please select a residential status">
                                <option value="">-- Please select --</option>
                                @foreach ($residentialStatuses as $status)
                                    <option value="{!!$status['id']!!}">{!!$status['description']!!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {!! Form::token() !!}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <a class="btn btn-info btn-block" data-target="save" data-source="ajax">Save Address</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
