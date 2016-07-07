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
            @if(isset($options) && count($options) > 0)
                <button class="btn btn-primary btn-lg pull-right" type="button" data-toggle="collapse" data-target="#extraInformation" aria-expanded="false" aria-controls="collapseExample">
                    <span class="glyphicon glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                </button>
                <div class="collapse" id="extraInformation">
                    <hr>
                    <div class="form-group collapse-form-group col-xs-12">
                        {!! Form::label('reference', 'Reference:', ['class' => 'col-sm-2 control-label text-right collapse-form-label']) !!}
                        <div class="col-xs-10">
                            {!! Form::text('reference', $reference, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
                        </div>
                    </div>
                    <div class="form-group collapse-form-group col-xs-12">
                        {!! Form::label('title', 'Title:', ['class' => 'col-sm-2 control-label text-right collapse-form-label']) !!}
                        <div class="col-xs-10">
                            <select class="form-control col-xs-12 collapse-form-input" name="title">
                                <option disabled selected hidden>Please select...</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Miss">Miss</option>
                                <option value="Ms">Ms</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group collapse-form-group col-xs-12">
                        {!! Form::label('first_name', 'First Name:', ['class' => 'col-sm-2 control-label text-right collapse-form-label']) !!}
                        <div class="col-xs-10">
                            {!! Form::text('first_name', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
                        </div>
                    </div>
                    <div class="form-group collapse-form-group col-xs-12">
                        {!! Form::label('last_name', 'Last Name:', ['class' => 'col-sm-2 control-label text-right collapse-form-label']) !!}
                        <div class="col-xs-10">
                            {!! Form::text('last_name', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
                        </div>
                    </div>
                    <div class="form-group collapse-form-group col-xs-12">
                        {!! Form::label('email', 'Email:', ['class' => 'col-sm-2 control-label text-right collapse-form-label']) !!}
                        <div class="col-xs-10">
                            {!! Form::email('email', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
                        </div>
                    </div>
                    <div class="form-group collapse-form-group col-xs-12">
                        {!! Form::label('phone_home', 'Home Phone:', ['class' => 'col-sm-2 control-label text-right collapse-form-label']) !!}
                        <div class="col-xs-10">
                            {!! Form::text('phone_home', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
                        </div>
                    </div>
                    <div class="form-group collapse-form-group col-xs-12">
                        {!! Form::label('phone_mobile', 'Mobile Phone:', ['class' => 'col-sm-2 control-label text-right collapse-form-label']) !!}
                        <div class="col-xs-10">
                            {!! Form::text('phone_mobile', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
                        </div>
                    </div>
                    <div class="form-group collapse-form-group col-xs-12">
                        {!! Form::label('postcode', 'Postcode:', ['class' => 'col-sm-2 control-label text-right collapse-form-label']) !!}
                        <div class="col-xs-10">
                            {!! Form::text('postcode', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
                        </div>
                    </div>
                </div>
            @endif
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
                                                <h2 id="loan-amount">&pound;{{ number_format($product['credit_info']['loan_amount']/100, 2) }}</h2> <p>loan amount</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #39b549; color: white;">
                                                <h2>&pound;{{ number_format($product['credit_info']['payment_regular']/100, 2) }}</h2> <p>monthly payment</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #1a1a1a; color: white;">
                                                <h2>&pound;{{ number_format($product['credit_info']['loan_cost']/100, 2) }}</h2> <p>total cost of credit variable</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #bbb; color: white;">
                                                <h2>&pound;{{ number_format($product['credit_info']['loan_repayment']/100, 2) }}</h2> <p>total repayable</p>
                                            </div>

                                        </div>
                                    </div>
                                    @if($product['product_group'] == 'BNPL')
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <table class="table table-condensed" style="font-size: 0.8em;">
                                                <tbody>
                                                <tr>
                                                    <th style="width: 50%;">Order Value</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['promotional']['order_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Deposit</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['promotional']['deposit_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Loan Amount</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['promotional']['loan_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Pay By</th>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $product['credit_info']['promotional']['date_end_iso'])->format('D jS M Y')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Settlement Fee</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['customer_settlement_fee']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cost</th>
                                                    <td>&pound;{{ number_format(($product['credit_info']['promotional']['deposit_amount'] + $product['credit_info']['promotional']['loan_amount'] + $product['credit_info']['promotional']['customer_settlement_fee'])/100, 2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <table class="table table-condensed" style="font-size: 0.8em;">
                                                <tbody>
                                                <tr>
                                                    <th style="width: 50%;">Order Value</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['order_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Loan Amount</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['loan_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Payment</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['payment_regular']/100, 2) }}</td>
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
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <table class="table table-condensed" style="font-size: 0.8em;">
                                                <tbody>
                                                <tr>
                                                    <th>Total Cost of Credit</th>
                                                    <td style="width: 50%;">&pound;{{ number_format($product['credit_info']['loan_cost']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Repayable</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['loan_repayment']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Deposit</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['deposit_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cost</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['total_cost']/100, 2) }}</td>
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
                                                    <td>&pound;{{ number_format($product['credit_info']['order_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Loan Amount</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['loan_amount']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Payment</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['payment_regular']/100, 2) }}</td>
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
                                                    <td style="width: 50%;">&pound;{{ number_format($product['credit_info']['loan_cost']/100, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Repayable</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['loan_repayment']/100, 2) }}</td>
                                                </tr>
                                                @if($product['credit_info']['amount_service'] > 0)
                                                    <tr>
                                                        <th>Service Fee</th>
                                                        <td>&pound;{{ number_format($product['credit_info']['amount_service']/100, 2) }}</td>
                                                    </tr>
                                                @endif
                                                @if($product['credit_info']['deposit_amount'] > 0)
                                                    <tr>
                                                        <th>Deposit</th>
                                                        <td>&pound;{{ number_format($product['credit_info']['deposit_amount']/100, 2) }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>Total Cost</th>
                                                    <td>&pound;{{ number_format($product['credit_info']['total_cost']/100, 2) }}</td>
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

                                    @foreach($location->installation->getBitwiseFinanceOffers() as $key => $offer)

                                        @if(count($bitwise->explode()) == 1)<div class="col-sm-12 col-xs-12">@endif
                                        @if(count($bitwise->explode()) == 2)<div class="col-sm-6 col-xs-12">@endif
                                        @if(count($bitwise->explode()) == 3)<div class="col-sm-4 col-xs-12">@endif
                                            @if($bitwise->contains($offer['value']))
                                                <button type="submit" class="btn btn-success btn-lg btn-block"@if(isset($offer['name'])) name="{!! $offer['name'] !!}" value="true"@endif>{!! $offer['text'] !!}</button>
                                            @endif
                                        </div>

                                    @endforeach

                                    @if($location->installation->disclosure)
                                        <br/>
                                        <div class="col-lg-12">
                                            {!! $location->installation->getDisclosureAsHtml() !!}
                                        </div>
                                    @endif

                                    {!! Form::hidden('amount', $amount) !!}
                                    {!! Form::hidden('product', $product['id']) !!}
                                    {!! Form::hidden('product_name', $product['name']) !!}
                                    {!! Form::hidden('group', $group['id']) !!}
                                    {!! Form::hidden('pay_today', $product['credit_info']['deposit_amount'] + $product['credit_info']['amount_service'], ['class' => 'pay_today']) !!}

                                    <!-- Extra Fields -->
                                    {!! Form::hidden('reference', '') !!}
                                    {!! Form::hidden('title', null) !!}
                                    {!! Form::hidden('first_name', null) !!}
                                    {!! Form::hidden('last_name', null) !!}
                                    {!! Form::hidden('email', null) !!}
                                    {!! Form::hidden('phone_home', null) !!}
                                    {!! Form::hidden('phone_mobile', null) !!}
                                    {!! Form::hidden('postcode', null) !!}
                                    <!-- End Extra Fields -->

                                {!! Form::close() !!}
                            </div>

                    @endforeach

                @endforeach
            @else
                <div class="alert alert-warning col-md-12" role="alert">No available products for this amount!</div>
            @endif

        @endif

    </div>
</div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>
    <script src={!! asset('/formvalidation/dist/js/formValidation.min.js') !!}></script>
    <script src={!! asset('/formvalidation/dist/js/framework/bootstrap.min.js') !!}></script>
    <script>
        $('li').click(function() {
            var prod = $(this).find('a').attr('aria-controls');
            var content = $('div#' + prod);
            var amount = $(content).find('.pay_today').attr('value');
            document.getElementById('pay-today').innerHTML = 'Pay Today £' + (amount / 100).toFixed(2);
        });
        $(window).bind("load", function() {
            if($('div.tab-pane.active').length > 0) {
                var div = $('div.tab-pane.active').first();
                var form = $(div).find('.pay_today');
                document.getElementById('pay-today').innerHTML = 'Pay Today £' + ($(form).attr('value') / 100).toFixed(2);
            }
        });
        $('.initialiseForm').submit(function() {
            var fields = ['reference', 'title', 'first_name', 'last_name', 'email', 'phone_home', 'phone_mobile', 'postcode'];
            fields.forEach(function(index) {
                var value = $('#extraInformation').find("input[name="+index+"]").val();
                $('.initialiseForm').find("input[name="+index+"]").attr('value', value);
            });
        });
        $(document).ready(function() {
            $('.form-inline').formValidation(
                    {
                        framework: 'bootstrap',
                        icon: {
                            valid: 'glyphicon glyphicon-ok',
                            invalid: 'glyphicon glyphicon-remove',
                            validating: 'glyphicon glyphicon-refresh'
                        },
                        fields: {
                            title: {
                                validators: {
                                    stringLength: {
                                        max: 4,
                                        message: 'You must select a valid title'
                                    }
                                }
                            },
                            first_name: {
                                validators: {
                                    stringLength: {
                                        max: 30,
                                        message: 'The first name must not be greater than 30 characters'
                                    }
                                }
                            },
                            last_name: {
                                validators: {
                                    stringLength: {
                                        max: 30,
                                        message: 'The last name must not be greater than 30 characters'
                                    }
                                }
                            },
                            email: {
                                validators: {
                                    emailAddress: {},
                                    stringLength: {
                                        max: 255,
                                        message: 'The email must not be greater than 255 characters'
                                    }
                                }
                            },
                            phone_home: {
                                validators: {
                                    phone: {
                                        country: "GB"
                                    }
                                }
                            },
                            phone_mobile: {
                                validators: {
                                    phone: {
                                        country: "GB"
                                    }
                                }
                            },
                            postcode: {
                                validators: {
                                    zipCode: {
                                        country: 'GB',
                                        message: 'The value is not valid %s postal code'
                                    }
                                }
                            }
                        }
                    }
            )
        });
    </script>
</div>
</body>
@endsection
