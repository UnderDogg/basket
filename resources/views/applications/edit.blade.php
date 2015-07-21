@extends('master')

@section('content')

    <h1>Edit Application</h1>
    <hr/>

    {!! Form::model($application, ['method' => 'PATCH', 'action' => ['ApplicationsController@update', $application->id], 'class' => 'form-horizontal']) !!}

    <div class="form-group">
                        {!! Form::label('requester', 'Requester: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('requester', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('installation_id', 'Installation Id: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('installation_id', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('location_id', 'Location Id: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('location_id', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_id', 'Ext Id: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_id', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_current_status', 'Ext Current Status: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_current_status', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_order_reference', 'Ext Order Reference: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_order_reference', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_order_amount', 'Ext Order Amount: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_order_amount', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_order_loan_amount', 'Ext Order Loan Amount: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_order_loan_amount', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_order_deposit', 'Ext Order Deposit: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_order_deposit', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_order_subsidy', 'Ext Order Subsidy: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_order_subsidy', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_order_net_settlement', 'Ext Order Net Settlement: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_order_net_settlement', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_order_description', 'Ext Order Description: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_order_description', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_order_validity', 'Ext Order Validity: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_order_validity', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_products_options', 'Ext Products Options: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_products_options', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_products_groups', 'Ext Products Groups: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_products_groups', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_products_default', 'Ext Products Default: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_products_default', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_fulfilment_method', 'Ext Fulfilment Method: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_fulfilment_method', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_fulfilment_location', 'Ext Fulfilment Location: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_fulfilment_location', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_customer_title', 'Ext Customer Title: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_customer_title', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_customer_first_name', 'Ext Customer First Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_customer_first_name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_customer_last_name', 'Ext Customer Last Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_customer_last_name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_customer_email_address', 'Ext Customer Email Address: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_customer_email_address', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_customer_phone_home', 'Ext Customer Phone Home: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_customer_phone_home', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_customer_phone_mobile', 'Ext Customer Phone Mobile: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_customer_phone_mobile', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_customer_postcode', 'Ext Customer Postcode: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_customer_postcode', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_application_address_abode', 'Ext Application Address Abode: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_application_address_abode', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_application_address_building_name', 'Ext Application Address Building Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_application_address_building_name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_application_address_building_number', 'Ext Application Address Building Number: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_application_address_building_number', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_application_address_street', 'Ext Application Address Street: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_application_address_street', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_application_address_locality', 'Ext Application Address Locality: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_application_address_locality', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_application_address_town', 'Ext Application Address Town: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_application_address_town', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_application_address_postcode', 'Ext Application Address Postcode: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_application_address_postcode', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_applicant_title', 'Ext Applicant Title: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_applicant_title', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_applicant_first_name', 'Ext Applicant First Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_applicant_first_name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_applicant_last_name', 'Ext Applicant Last Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_applicant_last_name', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_applicant_date_of_birth', 'Ext Applicant Date Of Birth: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_applicant_date_of_birth', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_applicant_email_address', 'Ext Applicant Email Address: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_applicant_email_address', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_applicant_phone_home', 'Ext Applicant Phone Home: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_applicant_phone_home', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_applicant_phone_mobile', 'Ext Applicant Phone Mobile: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_applicant_phone_mobile', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_applicant_postcode', 'Ext Applicant Postcode: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_applicant_postcode', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('ext_metadata', 'Ext Metadata: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('ext_metadata', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div><div class="form-group">
                        {!! Form::label('last_sync_at', 'Last Sync At: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6"> 
                            {!! Form::text('last_sync_at', null, ['class' => 'form-control']) !!}
                        </div>    
                    </div>
    
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

@endsection
