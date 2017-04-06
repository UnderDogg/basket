@extends('main')

@section('content')

    <h1>Applications
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/fulfil" class="btn btn-info{{ $fulfilmentAvailable == true && Auth::user()->can('applications-fulfil') ? ' ' : ' disabled' }}"><span class="glyphicon glyphicon-gift"></span> Fulfil</a>
            <a href="{{Request::url()}}/request-cancellation" class="btn btn-danger{{ $cancellationAvailable == true && Auth::user()->can('applications-cancel') ? ' ' : ' disabled' }}"><span class="glyphicon glyphicon-remove-circle"></span> Request Cancellation</a>
            @if(Auth::user()->can('applications-merchant-payments'))<a href="{{Request::url()}}/add-merchant-payment" class="btn btn-success{{ $merchantPaymentsAvailable == true && Auth::user()->can('applications-merchant-payments') ? ' ' : ' disabled' }}"><span class="glyphicon glyphicon-plus-sign"></span> Add Merchant Payment</a>@endif
            <a href="{{Request::url()}}/partial-refund" class="btn btn-warning{{   Auth::user()->can('applications-refund') ? '' : ' disabled' }}"><span class="glyphicon glyphicon-adjust"></span> Partial Refund</a>
        </div>
    </h1>
        @include('includes.page.breadcrumb', ['over' => [1 => $applications->installation->name], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#part1">Application Details</a></li>
            <li><a data-toggle="tab" href="#part2">Order Details</a></li>
            <li><a data-toggle="tab" href="#part3">Customer Details</a></li>
            @if(Auth::user()->can('applications-merchant-payments'))
                <li><a data-toggle="tab" href="#merchant-payments-pane">Merchant Payments</a></li>
            @endif
            @if(count($applicationHistory) > 0)
                <li><a data-toggle="tab" href="#application-history-pane">Application History</a></li>
            @endif
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
                        <dd>
                            @if(AppHelper::getApplicationStatusDescription($applications->ext_current_status) != '')<abbr title="{{ AppHelper::getApplicationStatusDescription($applications->ext_current_status) }}">@endif
                            <span class="{{ AppHelper::getApplicationStatusBackgroundColour($applications->ext_current_status)}} {{ AppHelper::getApplicationStatusTextColour($applications->ext_current_status) }}">
                                {{ AppHelper::getApplicationDisplayName($applications->ext_current_status) }}
                            </span>
                            @if($applications->ext_current_status != '')</abbr>@endif
                            @if($applications->ext_current_status == 'referred' && !empty($applications->ext_order_hold))&nbsp;<small>Customer contacted {!! ($applications->ext_order_hold instanceof \Carbon\Carbon) ? $applications->ext_order_hold->format('jS M Y H:i:s') : $applications->ext_order_hold !!} - awaiting customer response</small> @endif
                        </dd>

                        <dt>Order Reference</dt>
                        <dd>{{ $applications->ext_order_reference }}</dd>

                        @if($applications->user !== null)
                            <dt>Requester</dt>
                            <dd>{{ $applications->user->name }}</dd>
                        @endif

                        <dt>Installation</dt>
                        <dd>
                            @if(Auth::user()->can('merchants-view'))
                                <a href="{{Request::segment(0)}}/installations/{{$applications->installation->id}}">{{ $applications->installation->name }}</a>
                            @else
                                {{ $applications->installation->name }}
                            @endif
                        </dd>

                        @if($applications->location !== null)
                        <dt>Location</dt>
                        <dd>
                            @if(Auth::user()->can('locations-view'))
                                <a href="{{Request::segment(0)}}/locations/{{$applications->location->id}}">{{ $applications->location->name }}</a>
                            @else
                                {{ $applications->location->name }}
                            @endif
                        </dd>
                        @endif
                        </dl>
                    </div>
                </div>
                @if($applications->ext_resume_url && (in_array($applications->ext_current_status, [null, 'initialized', 'pending']) ))
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Application / Resume Link</strong></div>
                        <div class="panel-body">
                            <div class="input-group">
                                <input type="text" class="form-control disabled" value="{!!  $applications->ext_resume_url !!}" readonly>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions<span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        @if(!empty($applications->ext_applicant_email_address) || !empty($applications->ext_customer_email_address))
                                            <li><a href="{!! Request::url() !!}/email"><abbr title="Click to send email">Send Email</abbr></a></li>
                                        @endif
                                        <li><a id="return" href="{!!  $applications->ext_resume_url !!}"><abbr title="Click to copy the link">Copy Link</abbr></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
                    <div class="panel-heading"><strong>Documents</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            @if($applications->ext_is_regulated)
                            <dt>Pre-agreement</dt>
                            <dd>
                                @if($showDocuments)
                                    <a href="{!! Request::url() !!}/pre-agreement.pdf" target="_blank">
                                        <span class="glyphicon glyphicon-download-alt"></span>
                                        PDF
                                    </a>
                                @else
                                    <span>not available</span>
                                @endif
                            @endif
                            <dt>Agreement</dt>
                            <dd>
                                @if($showDocuments)
                                    <a href="{!! Request::url() !!}/agreement.pdf" target="_blank">
                                        <span class="glyphicon glyphicon-download-alt"></span>
                                        PDF
                                    </a>
                                @else
                                    <span>not available</span>
                                @endif
                            </dd>
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
                            <dt>Fulfilment Reference</dt>
                            <dd>{{ $applications->ext_fulfilment_reference }}</dd>
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
                            <dd>
                                @if(AppHelper::getApplicationStatusDescription($applications->ext_current_status) != '')<abbr title="{{ AppHelper::getApplicationStatusDescription($applications->ext_current_status) }}">@endif
                                    <span class="{{ AppHelper::getApplicationStatusBackgroundColour($applications->ext_current_status)}} {{ AppHelper::getApplicationStatusTextColour($applications->ext_current_status) }}">
                                        {{ AppHelper::getApplicationDisplayName($applications->ext_current_status) }}
                                    </span>
                                    @if($applications->ext_current_status != '')</abbr>@endif
                            </dd>
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
                            <dd>{{ $applications->ext_order_validity }}</dd>
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
                @if(!empty($applications->ext_applicant_first_name) && empty($applications->ext_customer_first_name))
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Applicant Details</strong></div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Title</dt>
                            <dd>{{ $applications->ext_applicant_title }}</dd>
                            <dt>First Name</dt>
                            <dd>{{ $applications->ext_applicant_first_name }}</dd>
                            <dt>Last Name</dt>
                            <dd>{{ $applications->ext_applicant_last_name }}</dd>
                            <dt>Date of Birth</dt>
                            <dd>{{ $applications->ext_applicant_date_of_birth }}</dd>
                            <dt>Email Address</dt>
                            <dd>{{ $applications->ext_applicant_email_address }}</dd>
                            <dt>Home Phone Number</dt>
                            <dd>{{ $applications->ext_applicant_phone_home }}</dd>
                            <dt>Mobile Phone Number</dt>
                            <dd>{{ $applications->ext_applicant_phone_mobile }}</dd>
                            <dt>Postcode</dt>
                            <dd>{{ $applications->ext_applicant_postcode }}</dd>
                        </dl>
                    </div>
                </div>
                @endif
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
            @if(Auth::user()->can('applications-merchant-payments'))
                <div id="merchant-payments-pane" class="tab-pane fade">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Merchant Payments</strong></div>
                    <div class="panel-body">
                        @if ($limit == count($merchantPayments))
                            <div class="alert alert-info">
                                This list has been truncated to display only the {{ $limit }} most recent payments,
                                but more transactions may have been created against this application.
                                If you need to view a full list of these transactions please use our API.
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Effective Date</th>
                                <th>Payment Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($merchantPayments as $payment)
                                <tr>
                                    <td>{{ $payment['effective_date'] }}</td>
                                    <td>{{ '&pound;' . number_format($payment['amount']/100, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>No merchant payments have been made.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            <div id="part4" class="tab-pane fade">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Application Events</strong></div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Event</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($applications->applicationEvents as $event)
                                <tr>
                                    <td>{{ (is_null($event->user) ? 'System' : $event->user->name) }}</td>
                                    <td>{{ $event->description }}</td>
                                    <td>{{ $event->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(count($applicationHistory) > 0)
            <div id="application-history-pane" class="tab-pane fade">
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Application Status History</strong></div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($applicationHistory as $historyItem)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $historyItem['status_friendly'])) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($historyItem['created_at'])->format('Y-m-d G:i:s') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

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

            hidden.textContent = elem.href;
            hidden.focus();
            hidden.setSelectionRange(0, hidden.value.length);
            return document.execCommand("copy");
        }

        if(window.location.hash != '') {
            $('a[href$='+ window.location.hash + ']').click();
        }
    </script>
    <script>
        validation = {
            fields: {
                title: {
                    validators: {
                        notEmpty: {
                            message: 'The title cannot be empty'
                        }
                    }
                },
                first_name: {
                    validators: {
                        notEmpty: {
                            message: 'The first name cannot be empty'
                        },
                        stringLength: {
                            max: 30,
                            message: 'The first name must not be greater than 30 characters'
                        }
                    }
                },
                last_name: {
                    validators: {
                        notEmpty: {
                            message: 'The last name cannot be empty'
                        },
                        stringLength: {
                            max: 30,
                            message: 'The last name must not be greater than 30 characters'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email address cannot be empty'
                        },
                        emailAddress: {},
                        stringLength: {
                            max: 255,
                            message: 'The email must not be greater than 255 characters'
                        }
                    }
                },
                subject: {
                    validators: {
                        notEmpty: {
                            message: 'The subject cannot be empty'
                        },
                        stringLength: {
                            max: 50,
                            message: 'The subject must not be greater than 50 characters'
                        }
                    }
                },
                description: {
                    validators: {
                        notEmpty: {
                            message: 'The description cannot be empty'
                        },
                        stringLength: {
                            max:100,
                            message: 'The description must not be greater than 100 characters'
                        }
                    }
                }
            }
        };
    </script>
@endsection
