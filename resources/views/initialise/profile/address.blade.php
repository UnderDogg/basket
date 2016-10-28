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
                        <input type="hidden" name="moved_in_date" />
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::selectMonth('month', null, ['id'=> 'moved_in_month','class' => 'form-control']) !!}
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::selectYear('year', \Carbon\Carbon::now()->year, \Carbon\Carbon::now()->subyears(30)->year, null, ['id'=> 'moved_in_year', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="moved-in-error col-sm-8 col-md-offset-2 col-xs-12"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('residential_status', 'Residential Status', ['class' => 'col-sm-2 control-label text-right']) !!}
                        <div class="col-sm-8">
                            <select class="form-control col-xs-12" name="residential_status">
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
            $('#address').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    date_of_birth: {
                        err: '.moved-in-error',
                        icon: false,
                        excluded: false,
                        validators: {
                            date: {
                                format: 'YYYY-MM-DD',
                                message: 'Please fully enter the moved in date'
                            }
                        }
                    }
                }
            });

            $('#address').on('change', '#moved_in_month, #moved_in_year', function(e) {
                var y = $('#address').find('#moved_in_year').val(),
                        m = $('#address').find('#moved_in_month').val(),
                        d = '01';
                $('#address').find('[name="moved_in_date"]').val(y === '' && m === '' ? '' : [y, m, d].join('-'));
                $('#address').formValidation('revalidateField', 'moved_in_date');
            });

            $('#moved_in_month').prepend( '<option value="">-- Month --</option>');
            $('#moved_in_year').prepend( '<option value="">-- Year --</option>');
            $('#moved_in_year option:last').text($('#moved_in_year option:last').text() + ' and earlier');
            $('#moved_in_month :nth-child(1)').prop('selected', true);
            $('#moved_in_year :nth-child(1)').prop('selected', true);
        });
    </script>
@endif
