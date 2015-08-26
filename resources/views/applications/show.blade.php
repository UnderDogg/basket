@extends('master')

@section('content')

    <div class="container">
    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(3))) }}
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/fulfil" class="btn btn-info{{ $fulfilmentAvailable == true ? ' ' : ' disabled' }}"><span class="glyphicon glyphicon-gift"></span> Fulfil</a>
            <a href="{{Request::url()}}/request-cancellation" class="btn btn-danger{{ $cancellationAvailable == true ? ' ' : ' disabled' }}"><span class="glyphicon glyphicon-remove-circle"></span> Request Cancellation</a>
            <a href="{{Request::url()}}/partial-refund" class="btn btn-warning{{ $partialRefundAvailable == true ? '' : ' disabled' }}"><span class="glyphicon glyphicon-adjust"></span> Partial Refund</a>
        </div>
    </h2>
        @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1 => $applications->installation->name]])
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#part1">Application Details</a></li>
            <li><a data-toggle="tab" href="#part2">Order Details</a></li>
            <li><a data-toggle="tab" href="#part3">Customer Details</a></li>
        </ul>

        <div class="tab-content">
            <div id="part1" class="tab-pane fade in active">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Key Information</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Application ID</dt>
                            <dd>{!! $applications->ext_id !!}</dd>
                            <dt>Current Status</dt>
                            <dd>{!! $applications->ext_current_status !!}</dd>
                            <dt>Order Reference</dt>
                            <dd>{!! $applications->ext_order_reference !!}</dd>
                            @if($applications->user !== null)
                                <dt>Requester</dt>
                                <dd>{!! $applications->user->name !!}</dd>
                            @endif
                            @if($applications->installation !== null)
                                <dt>Installations</dt>
                                <a href="{{Request::segment(0)}}/installations/{{$applications->installation->id}}">
                                    <dd>{!! $applications->installation->name !!}</dd>
                                </a>
                            @endif
                            @if($applications->location !== null)
                                <dt>Location</dt>
                                <a href="{{Request::segment(0)}}/locations/{{$applications->location->id}}">
                                    <dd>{!! $applications->location->name !!}</dd>
                                </a>
                            @endif
                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Product information</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            @if(isset($applications->ext_products_groups) && !empty($applications->ext_products_groups))
                                <dt>Product Group</dt>
                                <dd>{!! $applications->ext_products_groups !!}</dd>
                            @endif
                            @if(isset($applications->ext_products_options) && !empty($applications->ext_products_options))
                                <dt>Product Options</dt>
                                <dd>{!! $applications->ext_products_options !!}</dd>
                            @endif
                            @if(isset($applications->ext_products_default) && !empty($applications->ext_products_default))
                                <dt>Product Default</dt>
                                <dd>{!! $applications->ext_products_default !!}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Fulfilment Details</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            @if(isset($applications->ext_fulfilment_method) && !empty($applications->ext_fulfilment_method))
                                <dt>Fulfilment Method</dt>
                                <dd>{!! $applications->ext_fulfilment_method !!}</dd>
                            @endif
                            @if(isset($applications->ext_fulfilment_location) && !empty($applications->ext_fulfilment_location))
                                <dt>Fulfilment Location</dt>
                                <dd>{!! $applications->ext_fulfilment_location !!}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
                @if(isset($applications->ext_metadata) && !empty($applications->ext_metadata))
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Metadata</strong></div>
                    <div class="panel-body">
                        @include('includes.form.json', ['json' => json_decode($applications->ext_metadata, true)])
                    </div>
                </div>
                @endif
            </div>
            <div id="part2" class="tab-pane fade">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Order Details</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            @if(isset($applications->ext_customer_title) && !empty($applications->ext_customer_title))
                                <dt>PayBreak App ID</dt>
                                <dd>{!! $applications->ext_id !!}</dd>
                            @endif
                            @if(isset($applications->ext_current_status) && !empty($applications->ext_current_status))
                                <dt>Current Status</dt>
                                <dd>{!! $applications->ext_current_status !!}</dd>
                            @endif
                            @if(isset($applications->ext_order_description) && !empty($applications->ext_order_description))
                                <dt>Order Description</dt>
                                <dd>{!! $applications->ext_order_description !!}</dd>
                            @endif
                            @if(isset($applications->ext_finance_order_amount) && !empty($applications->ext_finance_order_amount))
                                <dt>Order Amount</dt>
                                <dd>{!! '&pound;' . number_format($applications->ext_finance_order_amount/100, 2) !!}</dd>
                            @endif
                            @if(isset($applications->ext_finance_loan_amount) && !empty($applications->ext_finance_loan_amount))
                                <dt>Loan Amount</dt>
                                <dd>{!! '&pound;' . number_format($applications->ext_finance_loan_amount/100, 2) !!}</dd>
                            @endif
                            @if(isset($applications->ext_finance_deposit) && !empty($applications->ext_finance_deposit))
                                <dt>Deposit</dt>
                                <dd>{!! '&pound;' . number_format($applications->ext_finance_deposit/100, 2) !!}</dd>
                            @endif
                            @if(isset($applications->ext_finance_subsidy) && $applications->ext_finance_subsidy)
                                <dt>Subsidy</dt>
                                <dd>{!! '&pound;' . number_format($applications->ext_finance_subsidy/100, 2) !!}</dd>
                            @endif
                            @if(isset($applications->ext_finance_net_settlement) && !empty($applications->ext_finance_net_settlement))
                                <dt>Net Settle Amount</dt>
                                <dd>{!! '&pound;' . number_format($applications->ext_finance_net_settlement/100, 2) !!}</dd>
                            @endif
                            @if(isset($applications->ext_validity) && !empty($applications->ext_validity))
                                <dt>Validity</dt>
                                <dd>{!! $applications->ext_validity !!}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
            <div id="part3" class="tab-pane fade">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Customer Details</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            @if(isset($applications->ext_customer_title) && !empty($applications->ext_customer_title))
                                <dt>Title</dt>
                                <dd>{!! $applications->ext_customer_title !!}</dd>
                            @endif
                            @if(isset($applications->ext_customer_first_name) && !empty($applications->ext_customer_first_name))
                                <dt>First Name</dt>
                                <dd>{!! $applications->ext_customer_first_name !!}</dd>
                            @endif
                            @if(isset($applications->ext_customer_last_name) && !empty($applications->ext_customer_last_name))
                                <dt>Last Name</dt>
                                <dd>{!! $applications->ext_customer_last_name !!}</dd>
                            @endif
                            @if(isset($applications->ext_customer_email_address) && !empty($applications->ext_customer_email_address))
                                <dt>Email Address</dt>
                                <dd>{!! $applications->ext_customer_email_address !!}</dd>
                            @endif
                            @if(isset($applications->ext_customer_phone_home) && !empty($applications->ext_customer_phone_home))
                                <dt>Home Phone Number</dt>
                                <dd>{!! $applications->ext_customer_phone_home !!}</dd>
                            @endif
                            @if(isset($applications->ext_customer_phone_mobile) && !empty($applications->ext_customer_phone_mobile))
                                <dt>Mobile Phone Number</dt>
                                <dd>{!! $applications->ext_customer_phone_mobile !!}</dd>
                            @endif
                            @if(isset($applications->ext_customer_postcode) && !empty($applications->ext_customer_postcode))
                                <dt>Postcode</dt>
                                <dd>{!! $applications->ext_customer_postcode !!}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Application Address</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            @if(isset($applications->ext_application_address_abode) && !empty($applications->ext_application_address_abode))
                                <dt>Abode</dt>
                                <dd>{!! $applications->ext_application_address_abode !!}</dd>
                            @endif
                            @if(isset($applications->ext_application_address_building_name) && !empty($applications->ext_application_address_building_name))
                                <dt>Building Name</dt>
                                <dd>{!! $applications->ext_application_address_building_name !!}</dd>
                            @endif
                            @if(isset($applications->ext_application_address_building_number) && !empty($applications->ext_application_address_building_number))
                                <dt>Building Number</dt>
                                <dd>{!! $applications->ext_application_address_building_number !!}</dd>
                            @endif
                            @if(isset($applications->ext_application_address_street) && !empty($applications->ext_application_address_street))
                                <dt>Street</dt>
                                <dd>{!! $applications->ext_application_address_street !!}</dd>
                            @endif
                            @if(isset($applications->ext_application_address_locality) && !empty($applications->ext_application_address_locality))
                                <dt>Locality</dt>
                                <dd>{!! $applications->ext_application_address_locality !!}</dd>
                            @endif
                            @if(isset($applications->ext_application_address_town) && !empty($applications->ext_application_address_town))
                                <dt>Town / City</dt>
                                <dd>{!! $applications->ext_application_address_town !!}</dd>
                            @endif
                            @if(isset($applications->ext_application_address_postcode) && !empty($applications->ext_application_address_postcode))
                                <dt>Postcode</dt>
                                <dd>{!! $applications->ext_application_address_postcode !!}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
