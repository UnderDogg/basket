@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])
    <div class="container">
    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        @include('includes.page.show_details_button_group', ['id'=>$applications->id,'edit'=>true, 'fulfil' => $fulfilmentAvailable, 'cancel' => $cancellationAvailable, 'partialRefund' => $partialRefundAvailable])
    </h2>
        @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])
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
                            <dd>{!! $applications->id !!}</dd>
                            <dt>Order Reference</dt>
                            <dd>{!! $applications->ext_order_reference !!}</dd>
                            <dt>Requester</dt>
                            @if($applications->user !== null)
                                <dd>{!! $applications->user->name !!}</dd>
                            @else
                                <dd></dd>
                            @endif
                            <dt>Installations</dt>
                            @if($applications->installation !== null)
                                <a href="{{Request::segment(0)}}/installations/{{$applications->installation->id}}">
                                    <dd>{!! $applications->installation->name !!}</dd>
                                </a>
                            @else
                                <dd></dd>
                            @endif
                            <dt>Location</dt>
                            @if($applications->location !== null)
                                <dd>{!! $applications->location->name !!}</dd>
                            @else
                                <dd></dd>
                            @endif
                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Product information</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Product Group</dt>
                            <dd>{!! $applications->ext_products_groups !!}</dd>
                            <dt>Product Options</dt>
                            <dd>{!! $applications->ext_products_options !!}</dd>
                            <dt>Product Default</dt>
                            <dd>{!! $applications->ext_products_default !!}</dd>
                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Fulfilment Details</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Fulfilment Method</dt>
                            <dd>{!! $applications->ext_fulfilment_method !!}</dd>
                            <dt>Fulfilment Location</dt>
                            <dd>{!! $applications->ext_fulfilment_location !!}</dd>
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
                            <dt>PayBreak App ID</dt>
                            <dd>{!! $applications->ext_id !!}</dd>
                            <dt>Current Status</dt>
                            <dd>{!! $applications->ext_current_status !!}</dd>
                            <dt>Order Description</dt>
                            <dd>{!! $applications->ext_order_description !!}</dd>
                            <dt>Order Amount</dt>
                            <dd>{!! '&pound;' . number_format($applications->ext_finance_order_amount/100, 2) !!}</dd>
                            <dt>Loan Amount</dt>
                            <dd>{!! '&pound;' . number_format($applications->ext_finance_loan_amount/100, 2) !!}</dd>
                            <dt>Deposit</dt>
                            <dd>{!! '&pound;' . number_format($applications->ext_finance_deposit/100, 2) !!}</dd>
                            <dt>Subsidy</dt>
                            <dd>{!! '&pound;' . number_format($applications->ext_finance_subsidy/100, 2) !!}</dd>
                            <dt>Net Settle Amount</dt>
                            <dd>{!! '&pound;' . number_format($applications->ext_finance_net_settlement/100, 2) !!}</dd>
                            <dt>Validity</dt>
                            <dd>{!! $applications->ext_validity !!}</dd>
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
                            <dt>Title</dt>
                            <dd>{!! $applications->ext_customer_title !!}</dd>
                            <dt>First Name</dt>
                            <dd>{!! $applications->ext_customer_first_name !!}</dd>
                            <dt>Surname</dt>
                            <dd>{!! $applications->ext_customer_last_name !!}</dd>
                            <dt>Email Address</dt>
                            <dd>{!! $applications->ext_customer_email_address !!}</dd>
                            <dt>Home Phone Number</dt>
                            <dd>{!! $applications->ext_customer_phone_home !!}</dd>
                            <dt>Mobile Phone Number</dt>
                            <dd>{!! $applications->ext_customer_phone_mobile !!}</dd>
                            <dt>Postcode</dt>
                            <dd>{!! $applications->ext_customer_postcode !!}</dd>
                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Customer Address</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Abode</dt>
                            @if(isset($applications->ext_application_address_abode) && !empty($applications->ext_application_address_abode))
                                    <dd>{!! $applications->ext_application_address_abode !!}</dd>
                            @else
                                <dd></dd>
                            @endif
                            <dt>Building Name</dt>
                            @if(isset($applications->ext_application_address_building_name) && !empty($applications->ext_application_address_building_name))
                                <dd>{!! $applications->ext_application_address_building_name !!}</dd>
                            @else
                                <dd></dd>
                            @endif
                            <dt>Building Number</dt>
                            @if(isset($applications->ext_application_address_building_number) && !empty($applications->ext_application_address_building_number))
                                <dd>{!! $applications->ext_application_address_building_number !!}</dd>
                            @else
                                <dd></dd>
                            @endif
                            <dt>Street</dt>
                            <dd>{!! $applications->ext_application_address_street !!}</dd>
                            <dt>Locality</dt>
                            <dd>{!! $applications->ext_application_address_locality !!}</dd>
                            <dt>Town / City</dt>
                            <dd>{!! $applications->ext_application_address_town !!}</dd>
                            <dt>Postcode</dt>
                            <dd>{!! $applications->ext_application_address_postcode !!}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
