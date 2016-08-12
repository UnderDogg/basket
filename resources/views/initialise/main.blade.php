@extends('master')

@section('page')
<body>
@if(env('ENV_BANNER', false))
    @include('env-banner')
@endif
<div class="loading"></div>
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
            {!! Form::open(['class' => 'form-inline form-finance-info']) !!}
            <div class="form-group">
                <label class="input-lg">Price</label>

                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {!! Form::text('ui_amount', isset($amount)?number_format($amount/100,2,'.',''):null, ['class' => 'form-control input-lg', 'maxlength' => 10]) !!}
                    {!! Form::hidden('amount', isset($amount)?number_format($amount/100,2,'.',''):null, ['class' => 'form-control input-lg', 'maxlength' => 10]) !!}
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

                        <li role="presentation"{{ ($k == 0 && $l == 0)?' class=active':'' }}><a href="#prod-FF" aria-controls="prod-FF" role="tab" data-toggle="tab">Flexible Finance</a></li>

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
                                                <input type="number" maxlength="10" step="1" class="form-control input-number" name="deposit" data-ajaxfield="deposit_amount" data-token="{{ csrf_token()}}" data-orderamt="{{ $product['credit_info']['order_amount']/100 }}" data-installation="{{ $location->installation->id }}" data-product="{{ $product['id'] }}" data-group="{{ $product['id'] }}" value="{{ ceil($product['credit_info']['deposit_amount']/100) }}" min="{{ ceil($product['credit_info']['deposit_range']['minimum_amount']/100) }}" max="{{ floor($product['credit_info']['deposit_range']['maximum_amount']/100) }}">
                                            </div>
                                            <div id="slider-range"></div>
                                        </div>
                                            {{--<input type="range" step="1" name="deposit_slide" id="deposit_slide" data-ajaxfield="deposit_amount" data-highlight="true" data-token="{{ csrf_token()}}" data-orderamt="{{ $product['credit_info']['order_amount']/100 }}" data-installation="{{ $location->installation->id }}" data-product="{{ $product['id'] }}" data-group="{{ $product['id'] }}" value="{{ ceil($product['credit_info']['deposit_amount']/100) }}" min="{{ ceil($product['credit_info']['deposit_range']['minimum_amount']/100) }}" max="{{ floor($product['credit_info']['deposit_range']['maximum_amount']/100) }}">--}}
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

                <div role="tabpanel" class="tab-pane" id="prod-FF">
                    {!! Form::open(['action' => ['InitialisationController@request', $location->id], 'class' => 'initialiseForm']) !!}
                    <h2>Flexible Finance</h2>
                    <div class="form-group container-fluid">
                        <div class="row text-center">

                            <div class="pay-today" style="display:none;">£9.99</div>

                            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 well" style="padding: 0px 60px 60px 60px;">
                                <h2>Holiday</h2>
                                <div id="slider-range-holiday"></div>
                                <br><br>
                                <h2>Term</h2>
                                <div id="slider-range-term"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-12" data-product="FF">
                                    <div class="form-group container-fluid">
                                        <div class="row text-center">

                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #29abe1; color: white;">
                                                <h2 id="loan-amount" data-fieldtype="currency" data-ajaxfield="ff_loan_amount"></h2> <p>loan amount</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #39b549; color: white;">
                                                <h2 data-fieldtype="currency" data-ajaxfield="ff_payment_regular"></h2> <p>monthly payment</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #1a1a1a; color: white;">
                                                <h2 data-fieldtype="currency" data-ajaxfield="ff_loan_cost"></h2> <p>total cost of credit <span class="hidden-xs">variable</span></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #bbb; color: white;">
                                                <h2 data-fieldtype="currency" data-ajaxfield="ff_loan_repayment"></h2> <p>total repayable</p>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <table class="table table-condensed" style="font-size: 0.8em;">
                                            <tbody>
                                            <tr>
                                                <th style="width: 50%;">Order Value</th>
                                                <td data-fieldtype="currency" data-ajaxfield="ff_order_amount"></td>
                                            </tr>
                                            <tr>
                                                <th>Loan Amount</th>
                                                <td data-fieldtype="currency" data-ajaxfield="ff_loan_amount"></td>
                                            </tr>
                                            <tr>
                                                <th>Monthly Payment</th>
                                                <td data-fieldtype="currency" data-ajaxfield="ff_payment_regular"></td>
                                            </tr>
                                            <tr>
                                                <th>No of Payments</th>
                                                <td data-fieldtype="raw" data-ajaxfield="ff_payments"></td>
                                            </tr>
                                            <tr>
                                                <th>Payment Start</th>
                                                <td data-fieldtype="date" data-ajaxfield="ff_payment_start_iso"></td>
                                            </tr>
                                            <tr>
                                                <th>Payment Ends</th>
                                                <td data-fieldtype="hybriddate" data-ajaxfield="ff_payment_start_iso" data-deltamonths="payments"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <table class="table table-condensed" style="font-size: 0.8em;">
                                            <tbody>
                                            <tr>
                                                <th>Total Cost of Credit</th>
                                                <td style="width: 50%;" data-fieldtype="currency" data-ajaxfield="ff_loan_cost"></td>
                                            </tr>
                                            <tr>
                                                <th>Total Repayable</th>
                                                <td data-fieldtype="currency" data-ajaxfield="ff_loan_repayment"></td>
                                            </tr>
                                            @if(true)
                                                <tr>
                                                    <th>Service Fee</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="ff_amount_service"></td>
                                                </tr>
                                            @endif
                                            @if(true)
                                                <tr>
                                                    <th>Deposit</th>
                                                    <td data-fieldtype="currency" data-ajaxfield="ff_deposit_amount"></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th>Total Cost</th>
                                                <td data-fieldtype="currency" data-ajaxfield="ff_total_cost"></td>
                                            </tr>
                                            <tr>
                                                <th>Interest Rate</th>
                                                <td data-fieldtype="percent" data-ajaxfield="ff_offered_rate">%</td>
                                            </tr>
                                            <tr>
                                                <th>APR</th>
                                                <td data-fieldtype="percent" data-ajaxfield="ff_apr">%</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <script src="{!! Bust::cache('/js/main.js') !!}"></script>
    <script src="{!! Bust::cache('/js/custom-deposit.main.js') !!}"></script>
    <script src="{!! Bust::cache('/js/sweetalert.min.js') !!}"></script>
    <script>
        $(document).ready(function() {
            $('li').click(function() {
                var prod = $(this).find('a').attr('aria-controls');
                var content = $('div#' + prod);
                var amount = $(content).find('.pay_today').attr('value');
                console.log($(content).find('.pay_today').attr('value'));
                document.getElementById('pay-today').innerHTML = 'Pay Today £' + parseFloat((Math.ceil(amount/100))).toFixed(2);
            });
            $(window).bind("load", function() {
                if($('div.tab-pane.active').length > 0) {
                    var div = $('div.tab-pane.active').first();
                    var form = $(div).find('.pay_today');
                    document.getElementById('pay-today').innerHTML = 'Pay Today £' + parseFloat((Math.ceil($(form).attr('value')/100))).toFixed(2);
                }
            });
            // Make sure the number input is parsed
            $('.form-finance-info').submit(function(e) {
                var uifield = $('.form-finance-info').first().find('input[name=ui_amount]');
                var field = $('.form-finance-info').first().find('input[name=amount]');
                var number = $(uifield).val();
                $(field).val(parseFloat(number.replace(',','')));
            });

            $('input[name=ui_amount]').on('keydown', function(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode;
                if (evt.shiftKey) {return false;}
                if (evt.altKey) {return false;}
                if (evt.ctrlKey) {return false;}
                if (evt.metaKey) {return false;}
                if (charCode > 31 && charCode != 190 && charCode != 37 && charCode != 39 && (charCode != 46 &&(charCode < 48 || charCode > 57)))
                    return false;
                return true;
            });

            var rangeSliderHoliday = document.getElementById('slider-range-holiday');
            var rangeSliderTerm = document.getElementById('slider-range-term');

            var rangeHoliday = {
                'min': [ 1 ],
                'max': [ 3 ]
            };

            var rangeTerm = {
                'min': [  3 ],
                'max': [ 11 ]
            };

            noUiSlider.create(rangeSliderHoliday, {
                start: 1,
                step: 1,
                margin: 0,
                tooltips: false,
                behaviour: 'tap',
                connect: 'lower',
                format: wNumb({decimals: 0}),
                orientation: "horizontal",
                range: rangeHoliday,
                pips: {
                    mode: 'values',
                    values: range(1,3),
                    density: 100
                }
            });

            noUiSlider.create(rangeSliderTerm, {
                start: 3,
                step: 1,
                margin: 0,
                tooltips: false,
                behaviour: 'tap',
                connect: 'lower',
                format: wNumb({decimals: 0}),
                orientation: "horizontal",
                range: rangeTerm,
                pips: {
                    mode: 'values',
                    values: range(3, 11),
                    density: 100
                }
            });

            rangeSliderHoliday.noUiSlider.on('change', function(values){
                var min = 3;
                var max = 12 - values[0];
                updateTermSliderRange(range(min, max), min, max);
                sliderUpdated();
            });

            rangeSliderTerm.noUiSlider.on('change', function(values){

                sliderUpdated();
            });

            getFlexibleFinanceQuote(1, 3);
        });

        function range(start, end) {
            var foo = [];
            for (var i = start; i <= end; i++) {
                foo.push(i);
            }
            return foo;
        }

        function getFlexibleFinanceQuote(holiday, term) {

            var loading = $(".loading").show();
            showLoading();

            $.post(
                "/ajax/installations/{{ $location->installation->id }}/products/AIN" + holiday + "-" + term + "/get-credit-info",
                { order_amount: {{ $amount or 0 }}, deposit: 0 }
            ).done(function( data ) {
                hideLoading();
                updateView(data);
            }).fail(function() {
                hideLoading();
                swal(
                    {
                        title: "An Error Occurred!",
                        text: "We were unable to recalculate information for the requested order. Please refresh the page.",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Refresh",
                        closeOnConfirm: false
                    },
                    function(){
                        location.reload();
                    }
                );
            });

            loading.hide();
        }

        function updateView(params) {
            console.log(params);

            $("[data-product='FF'] [data-ajaxfield]").each(function(){

                var content = params[$(this).data('ajaxfield').replace('ff_', '')];

                switch ($(this).data('fieldtype')) {
                    case 'hybriddate':
                        $(this).html(formatDate(content, params[$(this).data('deltamonths')]));
                        break;
                    case 'date':
                        $(this).html(formatDate(content, 0));
                        break;
                    case 'raw':
                        $(this).html(content);
                        break;
                    case 'percent':
                        $(this).html(content + "%");
                        break;
                    case 'currency':
                        $(this).html("£" + (content / 100));
                        break;
                }
            });
        }

        function updateTermSliderRange (values, min, max) {

            var rangeSliderTerm = document.getElementById('slider-range-term');

            var value = rangeSliderTerm.noUiSlider.get();

            rangeSliderTerm.noUiSlider.destroy();

            noUiSlider.create(rangeSliderTerm, {
                step: 1,
                start: 3,
                margin: 0,
                tooltips: false,
                behaviour: 'tap',
                connect: 'lower',
                format: wNumb({decimals: 0}),
                orientation: "horizontal",
                range: {
                    'min': [ min ],
                    'max': [ max ]
                },
                pips: {
                    mode: 'values',
                    values: values,
                    density: 15,
                }
            });

            rangeSliderTerm.noUiSlider.set(value);

            rangeSliderTerm.noUiSlider.on('change', function(values){

                sliderUpdated();
            });
        }

        function formatDate(dateStartIso, deltaMonths) {
            var date = new Date(Date.parse(dateStartIso))

            date.setMonth(date.getMonth() + deltaMonths);

            return date.toDateString();
        }

        function sliderUpdated() {
            var rangeSliderHoliday = document.getElementById('slider-range-holiday').noUiSlider.get();
            var rangeSliderTerm = document.getElementById('slider-range-term').noUiSlider.get();

            console.log(rangeSliderHoliday, rangeSliderTerm);

            getFlexibleFinanceQuote(rangeSliderHoliday, rangeSliderTerm);
        }
    </script>
    <style>
        #slider-range-term {
            background: #29ABE2;
        }

        #slider-range-holiday {
            background: #38B54A;
        }

        .noUi-handle:focus {
            box-shadow: 0 0 5px #29ABE2;
        }

        .noUi-value-large {
            margin-top: 8px;
        }

        div.container {
            width: 500px;
            margin: 50px auto;
            padding: 50px 50px;
            border: 1px solid #BFBFBF;
        }


        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            display: none;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.3);
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 1500ms infinite linear;
            -moz-animation: spinner 1500ms infinite linear;
            -ms-animation: spinner 1500ms infinite linear;
            -o-animation: spinner 1500ms infinite linear;
            animation: spinner 1500ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
</div>
</body>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{!! Bust::cache('/css/sweetalert.css') !!}">
    <link href="{!! Bust::cache('/css/nouislider.min.css') !!}" rel="stylesheet">
    <link href="{!! Bust::cache('/css/nouislider.tooltips.css') !!}" rel="stylesheet">
    <link href="{!! Bust::cache('/css/nouislider.pips.css') !!}" rel="stylesheet">
    <script src="{!! Bust::cache('/js/nouislider.min.js') !!}"></script>
    <script src="{!! Bust::cache('/js/wNumb.js') !!}"></script>
@endsection
