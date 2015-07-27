@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        @if($applications !== null)
            @include('includes.page.show_details_button_group', ['id'=>$applications->id,'edit'=>true, 'fulfil' => $fulfilmentAvailable, 'cancel' => $cancellationAvailable])
        @endif
    </h2>
    @include('includes.page.breadcrumb')

    <div id="basketTabs">
        <ul class="nav nav-tabs">
            <li role="presentation" class="tabbutton active"><a href="#fragment-1"><h5>Application Details</h5></a></li>
            <li role="presentation" class="tabbutton"><a href="#fragment-2"><h5>Order Details</h5></a></li>
            <li role="presentation" class="tabbutton"><a href="#fragment-3"><h5>Customer Details</h5></a></li>
        </ul>
        <div class="col-xs-12">&nbsp;</div>
        <hr>
        {{--FIRST PANEL: ROLE DETAILS--}}

        <div id="fragment-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Key information</h3>
                </div>
                <div class="panel-body">
                    @if($applications !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Application ID: </strong> {{ $applications->id }}
                            </li>
                            <li class="list-group-item">
                                <strong>Order Reference: </strong> {{ $applications->ext_order_reference }}
                            </li>
                            <li class="list-group-item">
                                <strong>Requester: </strong>
                                <a href="{{Request::segment(0)}}/user/{{$applications->user->id}}">
                                    {{ $applications->user->name }}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <strong>Installation: </strong>
                                <a href="{{Request::segment(0)}}/installations/{{$applications->installation->id}}">
                                    {{ $applications->installation->name }}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <strong>Location: </strong>
                                <a href="{{Request::segment(0)}}/locations/{{$applications->location->id}}">
                                    {{ $applications->location->name }}
                                </a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Product information</h3>
                </div>
                <div class="panel-body">
                    @if($applications !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Product Group: </strong> {{ $applications->ext_products_groups }}
                            </li>
                            <li class="list-group-item">
                                <strong>Product Options: </strong> {{ $applications->ext_products_options }}
                            </li>
                            <li class="list-group-item">
                                <strong>Product default: </strong> {{ $applications->ext_products_default }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Fulfilment Details</h3>
                </div>
                <div class="panel-body">
                    @if($applications !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Product Group: </strong> {{ $applications->ext_fulfilment_method }}
                            </li>
                            <li class="list-group-item">
                                <strong>Product Options: </strong> {{ $applications->ext_fulfilment_location }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>

            @if(isset($applications->ext_metadata) && !empty($applications->ext_metadata))
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Metadata</h3>
                </div>
                <div class="panel-body">
                    @if($applications !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Product Group: </strong> {{ $applications->ext_metadata }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div id="fragment-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Order Details</h3>
                </div>
                <div class="panel-body">
                    @if($applications !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>PayBreak Application ID: </strong> {{ $applications->ext_id }}
                            </li>
                            <li class="list-group-item">
                                <strong>Current Status: </strong> {{ $applications->ext_current_status }}
                            </li>
                            <li class="list-group-item">
                                <strong>Order Description: </strong> {{ $applications->ext_order_description }}
                            </li>
                            <li class="list-group-item">
                                <strong>Order Amount: </strong> {{ '£' . number_format($applications->ext_finance_order_amount/100, 2) }}
                            </li>
                            <li class="list-group-item">
                                <strong>Loan Amount: </strong> {{ '£' . number_format($applications->ext_finance_loan_amount/100, 2) }}
                            </li>
                            <li class="list-group-item">
                                <strong>Deposit: </strong> {{ '£' . number_format($applications->ext_finance_deposit/100, 2) }}
                            </li>
                            <li class="list-group-item">
                                <strong>Subsidy: </strong> {{ '£' . number_format($applications->ext_finance_subsidy/100, 2) }}
                            </li>
                            <li class="list-group-item">
                                <strong>Net Settlement: </strong> {{ '£' . number_format($applications->ext_finance_net_settlement/100, 2) }}
                            </li>
                            <li class="list-group-item">
                                <strong>Validity: </strong> {{ $applications->ext_validity }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div id="fragment-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Customer Details</h3>
                </div>
                <div class="panel-body">
                    @if($applications !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Title: </strong> {{ $applications->ext_customer_title }}
                            </li>
                            <li class="list-group-item">
                                <strong>First Name: </strong> {{ $applications->ext_customer_first_name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Surname: </strong> {{ $applications->ext_customer_last_name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Email Address: </strong> {{ $applications->ext_customer_email_address }}
                            </li>
                            <li class="list-group-item">
                                <strong>Home Phone Number: </strong> {{ $applications->ext_customer_phone_home }}
                            </li>
                            <li class="list-group-item">
                                <strong>Mobile Phone Number: </strong> {{ $applications->ext_customer_phone_mobile }}
                            </li>
                            <li class="list-group-item">
                                <strong>Postcode: </strong> {{ $applications->ext_customer_postcode }}
                            </li>

                        </ul>
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Customer Address</h3>
                </div>
                <div class="panel-body">
                    @if($applications !== null)
                        <ul class="list-group">
                            @if(isset($applications->ext_application_address_abode) && !empty($applications->ext_application_address_abode))
                                <li class="list-group-item">
                                    <strong>Abode: </strong> {{ $applications->ext_application_address_abode }}
                                </li>
                            @endif
                            @if(isset($applications->ext_application_address_building_name) && !empty($applications->ext_application_address_building_name))
                                <li class="list-group-item">
                                    <strong>Building Name: </strong> {{ $applications->ext_application_address_building_name }}
                                </li>
                            @endif
                            @if(isset($applications->ext_application_address_building_number) && !empty($applications->ext_application_address_building_number))
                                <li class="list-group-item">
                                    <strong>Building Number: </strong> {{ $applications->ext_application_address_building_number }}
                                </li>
                            @endif
                            <li class="list-group-item">
                                <strong>Street: </strong> {{ $applications->ext_application_address_street }}
                            </li>
                            <li class="list-group-item">
                                <strong>Locality: </strong> {{ $applications->ext_application_address_locality }}
                            </li>
                            <li class="list-group-item">
                                <strong>Town/City: </strong> {{ $applications->ext_application_address_town }}
                            </li>
                            <li class="list-group-item">
                                <strong>Postcode: </strong> {{ $applications->ext_application_address_postcode }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
