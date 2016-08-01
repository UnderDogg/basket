@extends('master')

@section('page')
<body>
@if(env('ENV_BANNER', false))
    @include('env-banner')
@endif
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="pull-left">
                    <a href="/">
                        {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    @if($location->installation->custom_logo_url)
                        {!! HTML::image($location->installation->custom_logo_url, 'logo') !!}
                    @endif
                </div>
            </div>
        </div>
        <br/>
        @include('includes.message.action_response')
        <h1>Interested In Finance?</h1>
        <div class="col-md-12 well">
            {!! Form::open(['class' => 'form-inline']) !!}
            <div class="form-group">
                <label class="input-lg">Price</label>

                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {!! Form::text('amount', isset($amount)?number_format($amount/100,2):null, ['class' => 'form-control input-lg', 'maxlength' => 10]) !!}
                </div>

            </div>

            <button id="finance-options" type="submit" class="btn btn-primary btn-lg lg-font-btn">Show Finance Options</button>
            <div class="form-group padding-left-form">
                <h4><strong id="pay-today"></strong></h4>
            </div>
            {!! Form::close() !!}
        </div>
        @if(isset($options))

            @if(count($options) > 0)

                @if(count($options) > 1 || count($options[0]['products']) > 1)
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($options as $k => $group)

                            @foreach($group['products'] as $l => $product)

                                <li role="presentation"{{ ($k == 0 && $l == 0)?' class=active':'' }}><a href="#prod-{{$product['id']}}" aria-controls="prod-{{$product['id']}}" role="tab" data-toggle="tab">{{$product['name']}}</a></li>

                            @endforeach

                        @endforeach
                    </ul>
                @endif

                <div class="tab-content">
                    @foreach($options as $k => $group)

                        @foreach($group['products'] as $l => $product)

                            <div role="tabpanel" class="tab-pane{{ ($k == 0 && $l == 0)?' active':'' }}" id="prod-{{$product['id']}}">
                                {!! Form::open(['action' => ['InitialisationController@request', $location->id], 'class' => 'initialiseForm']) !!}
                                    <h2>{{$product['name']}}</h2>
                                    <div class="form-group container-fluid">
                                        <div class="row text-center">

                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #29abe1; color: white;">
                                                <h2 id="loan-amount" data-fieldtype="currency" data-ajaxfield="loan_amount">&pound;{{ number_format($product['credit_info']['loan_amount']/100, 2) }}</h2> <p>loan amount</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #39b549; color: white;">
                                                <h2 data-fieldtype="currency" data-ajaxfield="payment_regular">&pound;{{ number_format($product['credit_info']['payment_regular']/100, 2) }}</h2> <p>monthly payment</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #1a1a1a; color: white;">
                                                <h2 data-fieldtype="currency" data-ajaxfield="loan_cost">&pound;{{ number_format($product['credit_info']['loan_cost']/100, 2) }}</h2> <p>total cost of credit <span class="hidden-xs">variable</span></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #bbb; color: white;">
                                                <h2 data-fieldtype="currency" data-ajaxfield="loan_repayment">&pound;{{ number_format($product['credit_info']['loan_repayment']/100, 2) }}</h2> <p>total repayable</p>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                    @if($product['product_group'] == 'BNPL')
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <table class="table table-condensed" style="font-size: 0.8em;">
                                                <tbody>
                                                <tr>
                                                    <th style="width: 50%;">Order Value</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="order_amount">&pound;{{ number_format($product['credit_info']['promotional']['order_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Deposit</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="deposit_amount">&pound;{{ number_format($product['credit_info']['promotional']['deposit_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Loan Amount</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="loan_amount">&pound;{{ number_format($product['credit_info']['promotional']['loan_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Pay By</th>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $product['credit_info']['promotional']['date_end_iso'])->format('D jS M Y')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Settlement Fee</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="customer_settlement_fee">&pound;{{ number_format($product['credit_info']['customer_settlement_fee']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cost</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="total_cost">&pound;{{ number_format(($product['credit_info']['promotional']['deposit_amount'] + $product['credit_info']['promotional']['loan_amount'] + $product['credit_info']['promotional']['customer_settlement_fee'])/100, 2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <table class="table table-condensed" style="font-size: 0.8em;">
                                                <tbody>
                                                <tr>
                                                    <th style="width: 50%;">Order Value</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="order_amount">&pound;{{ number_format($product['credit_info']['order_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Loan Amount</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="loan_amount">&pound;{{ number_format($product['credit_info']['loan_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Payment</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="payment_regular">&pound;{{ number_format($product['credit_info']['payment_regular']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>No of Payments</th>
                                                    <td data-fieldtype="number" data-ajaxfield="payments">{{ $product['credit_info']['payments'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Payment Start</th>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $product['credit_info']['payment_start_iso'])->format('D jS M Y')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Payment Ends</th>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $product['credit_info']['payment_start_iso'])->addMonths($product['credit_info']['payments'])->format('D jS M Y')}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <table class="table table-condensed" style="font-size: 0.8em;">
                                                <tbody>
                                                <tr>
                                                    <th>Total Cost of Credit</th>
                                                    <td style="width: 50%;" data-fieldtype="currency" data-ajaxfield="loan_cost">&pound;{{ number_format($product['credit_info']['loan_cost']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Repayable</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="loan_repayment">&pound;{{ number_format($product['credit_info']['loan_repayment']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Deposit</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="deposit_amount">&pound;{{ number_format($product['credit_info']['deposit_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cost</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="total_cost">&pound;{{ number_format($product['credit_info']['total_cost']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Interest Rate</th>
                                                    <td>{{ number_format($product['credit_info']['offered_rate'], 1) }}%</td>
                                                </tr>
                                                <tr>
                                                    <th>APR</th>
                                                    <td>{{ number_format($product['credit_info']['apr'], 1) }}%</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <table class="table table-condensed" style="font-size: 0.8em;">
                                                <tbody>
                                                <tr>
                                                    <th style="width: 50%;">Order Value</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="order_amount">&pound;{{ number_format($product['credit_info']['order_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Loan Amount</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="loan_amount">&pound;{{ number_format($product['credit_info']['loan_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Payment</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="payment_regular">&pound;{{ number_format($product['credit_info']['payment_regular']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>No of Payments</th>
                                                    <td>{{ $product['credit_info']['payments'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Payment Start</th>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $product['credit_info']['payment_start_iso'])->format('D jS M Y')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Payment Ends</th>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $product['credit_info']['payment_start_iso'])->addMonths($product['credit_info']['payments'])->format('D jS M Y')}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <table class="table table-condensed" style="font-size: 0.8em;">
                                                <tbody>
                                                <tr>
                                                    <th>Total Cost of Credit</th>
                                                    <td style="width: 50%;" data-fieldtype="currency" data-ajaxfield="loan_cost">&pound;{{ number_format($product['credit_info']['loan_cost']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Repayable</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="loan_repayment">&pound;{{ number_format($product['credit_info']['loan_repayment']/100, 2) }}</td>
                                                </tr>
                                                @if($product['credit_info']['amount_service'] > 0)
                                                    <tr>
                                                        <th>Service Fee</th>
                                                        <td data-fieldtype="currency" data-ajaxfield="amount_service">&pound;{{ number_format($product['credit_info']['amount_service']/100, 2) }}</td>
                                                    </tr>
                                                @endif
                                                @if($product['credit_info']['deposit_amount'] > 0)
                                                    <tr>
                                                        <th>Deposit</th>
                                                        <td data-fieldtype="currency" data-ajaxfield="deposit_amount">&pound;{{ number_format($product['credit_info']['deposit_amount']/100, 2) }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>Total Cost</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="total_cost">&pound;{{ number_format($product['credit_info']['total_cost']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Interest Rate</th>
                                                    <td>{{ number_format($product['credit_info']['offered_rate'], 1) }}%</td>
                                                </tr>
                                                <tr>
                                                    <th>APR</th>
                                                    <td>{{ number_format($product['credit_info']['apr'], 1) }}%</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    </div>

                                    @if($product['credit_info']['deposit_range']['minimum_amount'] <  $product['credit_info']['deposit_range']['maximum_amount'])

                                    <div class="row">
                                    <div class="col-sm-12 col-lg-12 col-xs-12 well" style="margin-top: 30px;padding-top:30px;">

                                        <h1>Deposit Amount</h1>
                                        <div class="col-sm-2 col-xs-12">
                                            <div class="input-group">
                                                <div class="input-group-addon">&pound;</div>
                                                <input type="number" step="1" class="form-control" name="deposit" data-ajaxfield="deposit_amount" data-token="{{ csrf_token()}}" data-orderamt="{{ $product['credit_info']['order_amount']/100 }}" data-installation="{{ $location->installation->id }}" data-product="{{ $product['id'] }}" data-group="{{ $product['id'] }}" value="{{ ceil($product['credit_info']['deposit_amount']/100) }}" min="{{ ceil($product['credit_info']['deposit_range']['minimum_amount']/100) }}" max="{{ floor($product['credit_info']['deposit_range']['maximum_amount']/100) }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-10 col-xs-12">
                                            <input type="range" step="1" name="deposit_slide" id="deposit_slide" data-ajaxfield="deposit_amount" data-highlight="true" data-token="{{ csrf_token()}}" data-orderamt="{{ $product['credit_info']['order_amount']/100 }}" data-installation="{{ $location->installation->id }}" data-product="{{ $product['id'] }}" data-group="{{ $product['id'] }}" value="{{ ceil($product['credit_info']['deposit_amount']/100) }}" min="{{ ceil($product['credit_info']['deposit_range']['minimum_amount']/100) }}" max="{{ floor($product['credit_info']['deposit_range']['maximum_amount']/100) }}">
                                        </div>
                                    </div>
                                    </div>

                                    @endif

                                    <div class="row">
                                        @if($bitwise->contains(2) && count($bitwise->explode()) > 1) <div class="col-sm-6 col-xs-12">@else <div class="col-sm-12 col-xs-12">@endif
                                            @if($bitwise->contains(2))
                                                <button type="submit" class="btn btn-success btn-lg btn-block btn-bottom-margin">Continue with In-store Application</button>
                                            @endif
                                        </div>
                                            @if($bitwise->contains(2) && count($bitwise->explode()) > 1) <div class="col-sm-6 col-xs-12">@else <div class="col-sm-12 col-xs-12">@endif
                                            @if($bitwise->contains(4) || $bitwise->contains(8))
                                                <button type="submit" class="btn btn-success btn-lg btn-block btn-bottom-margin" name="alternate" value="true">Create an Application Link</button>
                                            @endif
                                        </div>
                                    </div>

                                    @if($location->installation->disclosure)
                                        <br/>
                                        <div class="col-lg-12" style="font-size: 0.8em !important;">
                                            {!! $location->installation->getDisclosureAsHtml() !!}
                                        </div>
                                    @endif

                                    {!! Form::hidden('amount', $amount) !!}
                                    {!! Form::hidden('product', $product['id']) !!}
                                    {!! Form::hidden('product_name', $product['name']) !!}
                                    {!! Form::hidden('group', $group['id']) !!}
                                    {!! Form::hidden('pay_today', $product['credit_info']['deposit_amount'] + $product['credit_info']['amount_service'], ['class' => 'pay_today']) !!}
                                    {!! Form::hidden('reference', $reference) !!}
                                    {!! Form::hidden('description', 'Goods & Services') !!}

                                {!! Form::close() !!}
                            </div>

                    @endforeach

                @endforeach
            @else
                <div class="alert alert-warning col-md-12" role="alert">No available products for this amount!</div>
            @endif

        @endif

    </div>
    <div class="container loading-container">
        <button class="btn btn-lg btn-info"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</button>
    </div>
</div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/custom-deposit.main.js"></script>
    <script src="/js/sweetalert.min.js"></script>
    <script>
        $('li').click(function() {
            var prod = $(this).find('a').attr('aria-controls');
            var content = $('div#' + prod);
            var amount = $(content).find('.pay_today').attr('value');
            document.getElementById('pay-today').innerHTML = 'Pay Today £' + ceil(amount / 100).toFixed(2);
        });
        $(window).bind("load", function() {
            if($('div.tab-pane.active').length > 0) {
                var div = $('div.tab-pane.active').first();
                var form = $(div).find('.pay_today');
                document.getElementById('pay-today').innerHTML = 'Pay Today £' + ceil($(form).attr('value') / 100).toFixed(2);
            }
        });
    </script>

</div>
</body>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="/css/sweetalert.css">
@endsection
