@extends('main')

@section('content')

    <h1>Applications
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/fulfil" class="btn btn-info{{ $fulfilmentAvailable == true ? ' ' : ' disabled' }}"><span class="glyphicon glyphicon-gift"></span> Fulfil</a>
            <a href="{{Request::url()}}/request-cancellation" class="btn btn-danger{{ $cancellationAvailable == true ? ' ' : ' disabled' }}"><span class="glyphicon glyphicon-remove-circle"></span> Request Cancellation</a>
            <a href="{{Request::url()}}/partial-refund" class="btn btn-warning{{ $partialRefundAvailable == true ? '' : ' disabled' }}"><span class="glyphicon glyphicon-adjust"></span> Partial Refund</a>
        </div>
    </h1>
        @include('includes.page.breadcrumb', ['over' => [1 => $applications->installation->name], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#part1">Application Details</a></li>
            <li><a data-toggle="tab" href="#part2">Order Details</a></li>
            <li><a data-toggle="tab" href="#part3">Customer Details</a></li>
            <li><a data-toggle="tab" href="#part4">Event Log</a></li>
        </ul>

        <div class="tab-content">
            <div id="part1" class="tab-pane fade in active">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Key Information</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">

                            <dt>Application ID</dt>
                            <dd>{{ $applications->ext_id }}</dd>

                            <dt>Current Status</dt>
                            <dd>{{ ucwords($applications->ext_current_status) }}</dd>

                            <dt>Order Reference</dt>
                            <dd>{{ $applications->ext_order_reference }}</dd>

                            @if($applications->user !== null)
                                <dt>Requester</dt>
                                <dd>{{ $applications->user->name }}</dd>
                            @endif

                            <dt>Installations</dt>
                            <dd>
                                @if(Auth::user()->can('merchants-view'))
                                    <a href="{{Request::segment(0)}}/installations/{{$applications->installation->id}}">{{ $applications->installation->name }}</a>
                                @else
                                    {{ $applications->installation->name }}
                                @endif
                            </dd>

                            @if($applications->location !== null)
                                <dt>Location</dt>
                                @if(Auth::user()->can('locations-view'))
                                    <dd><a href="{{Request::segment(0)}}/locations/{{$applications->location->id}}">{{ $applications->location->name }}</a></dd>
                                @else
                                    {{ $applications->location->name }}
                                @endif
                            @endif

                            @if($applications->ext_resume_url && ($applications->ext_current_status == null || $applications->ext_current_status == 'initialized' || $applications->ext_current_status == 'pending'))
                                <dt>Resume URL</dt>
                                <dd><a href="" id="return" data-clipboard-text="{{$applications->ext_resume_url}}">{{$applications->ext_resume_url}}</a></dd>
                            @endif

                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Product information</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Product Group</dt>
                            <dd>{{ $applications->ext_products_groups }}</dd>
                            <dt>Product Options</dt>
                            <dd>{{ $applications->ext_products_options }}</dd>
                            <dt>Product Default</dt>
                            <dd>{{ $applications->ext_products_default }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Fulfilment Details</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Fulfilment Method</dt>
                            <dd>{{ $applications->ext_fulfilment_method }}</dd>
                            <dt>Fulfilment Location</dt>
                            <dd>{{ $applications->ext_fulfilment_location }}</dd>
                        </dl>
                    </div>
                </div>
                @if($applications->ext_current_status == 'pending_cancellation')
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Cancellation Details</strong></div>
                        <div class="panel-body">
                            <dl class="dl-horizontal">
                                <dt>Effective Date</dt>
                                <dd>{{ date('d/m/Y', strtotime($applications->ext_cancellation_effective_date)) }}</dd>
                                <dt>Requested Date</dt>
                                <dd>{{ date('d/m/Y H:i', strtotime($applications->ext_cancellation_requested_date)) }}</dd>
                                <dt>Description</dt>
                                <dd>{{ $applications->ext_cancellation_description }}</dd>
                                <dt>Cancellation Fee</dt>
                                <dd>{{ '&pound;' . number_format($applications->ext_cancellation_fee_amount/100, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                @endif
                @if(isset($applications->ext_metadata) && $applications->ext_metadata != "null")
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
                            <dd>{{ $applications->ext_id }}</dd>
                            <dt>Current Status</dt>
                            <dd>{{ $applications->ext_current_status }}</dd>
                            <dt>Order Description</dt>
                            <dd>{{ $applications->ext_order_description }}</dd>
                            <dt>Order Amount</dt>
                            <dd>{{ '&pound;' . number_format($applications->ext_finance_order_amount/100, 2) }}</dd>
                            <dt>Loan Amount</dt>
                            <dd>{{ '&pound;' . number_format($applications->ext_finance_loan_amount/100, 2) }}</dd>
                            <dt>Deposit</dt>
                            <dd>{{ '&pound;' . number_format($applications->ext_finance_deposit/100, 2) }}</dd>
                            <dt>Subsidy</dt>
                            <dd>{{ '&pound;' . number_format($applications->ext_finance_subsidy/100, 2) }}</dd>
                            <dt>Commission</dt>
                            <dd>{{ '&pound;' . number_format($applications->ext_finance_commission/100, 2) }}</dd>
                            <dt>Net Settle Amount</dt>
                            <dd>{{ '&pound;' . number_format($applications->ext_finance_net_settlement/100, 2) }}</dd>
                            <dt>Validity</dt>
                            <dd>{{ $applications->ext_validity }}</dd>
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
                            <dd>{{ $applications->ext_customer_title }}</dd>
                            <dt>First Name</dt>
                            <dd>{{ $applications->ext_customer_first_name }}</dd>
                            <dt>Last Name</dt>
                            <dd>{{ $applications->ext_customer_last_name }}</dd>
                            <dt>Email Address</dt>
                            <dd>{{ $applications->ext_customer_email_address }}</dd>
                            <dt>Home Phone Number</dt>
                            <dd>{{ $applications->ext_customer_phone_home }}</dd>
                            <dt>Mobile Phone Number</dt>
                            <dd>{{ $applications->ext_customer_phone_mobile }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Application Address</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Abode</dt>
                            @if(isset($applications->ext_application_address_abode) && !empty($applications->ext_application_address_abode))
                                <dd>{{ $applications->ext_application_address_abode }}</dd>
                            @else
                                <dd></dd>
                            @endif
                            <dt>Building Name</dt>
                            @if(isset($applications->ext_application_address_building_name) && !empty($applications->ext_application_address_building_name))
                                <dd>{{ $applications->ext_application_address_building_name }}</dd>
                            @else
                                <dd></dd>
                            @endif
                            <dt>Building Number</dt>
                            @if(isset($applications->ext_application_address_building_number) && !empty($applications->ext_application_address_building_number))
                                <dd>{{ $applications->ext_application_address_building_number }}</dd>
                            @else
                                <dd></dd>
                            @endif
                            <dt>Street</dt>
                            <dd>{{ $applications->ext_application_address_street }}</dd>
                            <dt>Locality</dt>
                            <dd>{{ $applications->ext_application_address_locality }}</dd>
                            <dt>Town / City</dt>
                            <dd>{{ $applications->ext_application_address_town }}</dd>
                            <dt>Postcode</dt>
                            <dd>{{ $applications->ext_application_address_postcode }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div id="part4" class="tab-pane fade">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Application Events</strong></div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Event</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($applicationEvents as $event)
                                    <tr>
                                        <td>{{ $event->description }}</td>
                                        <td>{{ $event->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <div class='toast' style='display:none'>Copied to clipboard!</div>
@endsection

@section('scripts')
    <script>
        $('#return').click(function(e) {
            e.preventDefault();
            copyToClipboard(document.getElementById("return"));
            $('.toast').text('Copied to clipboard!').fadeIn(400).delay(3000).fadeOut(400);
            return false;
        });

        function copyToClipboard(elem) {
            var hidden = document.createElement("textarea");
            hidden.style.position = "absolute";
            hidden.style.left = "-9999px";
            hidden.style.top = "0";
            document.body.appendChild(hidden);

            hidden.textContent = elem.textContent;
            hidden.focus();
            hidden.setSelectionRange(0, hidden.value.length);
            return document.execCommand("copy");
        }
    </script>
@endsection
