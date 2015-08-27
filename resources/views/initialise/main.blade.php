@extends('master')

@section('page')
<body>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="pull-left">
                    {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                </div>
            </div>
        </div>
        <br/>

        <h1>Interested In Finance?</h1>
        <div class="col-md-12 well">
            {!! Form::open(['class' => 'form-inline']) !!}
            <div class="form-group">
                <label>Price</label>

                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {!! Form::text('amount', isset($amount)?number_format($amount/100,2):null, ['class' => 'form-control', 'maxlength' => 10]) !!}
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Show Finance Options</button>
            {!! Form::close() !!}
        </div>

        @if(isset($options))

            @if(count($options) > 0)

                @if(count($options) != 1)
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
                                {!! Form::open(['action' => ['InitialisationController@request', $location]]) !!}
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
                                                <h2>{{ $product['payments'] }}</h2> <p>payments</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 col-lg-3 col-xs-6" style="background-color: #bbb; color: white;">
                                                <h2>{{ number_format($product['credit_info']['apr'], 1) }}%</h2> <p>APR</p>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <table class="table table-condensed" style="font-size: 0.8em;">
                                            <tbody><tr>
                                                <th>Payment Starts</th>
                                                <td style="width: 50%">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $product['credit_info']['payment_start_iso'])->format('D jS M Y') }}</td>
                                                {{--<td>{{ $product['credit_info']['payment_start_nice'] }}</td>--}}
                                            </tr>
                                            <tr>
                                                <th>Interest Rate (Fixed)</th>
                                                <td>{{ number_format($product['credit_info']['offered_rate'], 1) }}%</td>
                                            </tr>
                                            @if($product['credit_info']['apr'] !== 0)
                                            <tr>
                                                <th>APR</th>
                                                <td>{{ number_format($product['credit_info']['apr'], 1) }}%</td>
                                            </tr>
                                            @endif
                                            @if($product['credit_info']['deposit_amount'] > 1 && $product['credit_info']['amount_service'] == 0)
                                            <tr>
                                                <th>Deposit</th>
                                                <td>&pound;{{ number_format($product['credit_info']['deposit_amount']/100, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Loan Amount</th>
                                                <td>&pound;{{ number_format($product['credit_info']['loan_amount']/100, 2) }}</td>
                                            </tr>
                                            {{--<tr>--}}
                                                {{--<th>Minimum Deposit</th>--}}
                                                {{--<td>&pound;{{ number_format($product['credit_info']['deposit_range']['minimum_amount']/100, 2) }}</td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<th>Maximum Deposit</th>--}}
                                                {{--<td>&pound;{{ number_format($product['credit_info']['deposit_range']['maximum_amount']/100, 2) }}</td>--}}
                                            {{--</tr>--}}
                                            </tbody></table>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <table class="table table-condensed" style="font-size: 0.8em;">
                                            <tbody>
                                            <tr>
                                                <th>Monthly Payment</th>
                                                <td>&pound;{{ number_format($product['credit_info']['payment_regular']/100, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Months Payable</th>
                                                <td>{{ ($product['credit_info']['payments'])-1 }}</td>
                                            </tr>
                                            <tr>
                                                <th>Final Payment</th>
                                                <td>&pound;{{ number_format($product['credit_info']['payment_final']/100, 2) }}</td>
                                            </tr>
                                            @if($product['credit_info']['amount_service'] !== 0 && $product['credit_info']['deposit_amount'] > 1)
                                            <tr>
                                                <th>Service Payment</th>
                                                <td>&pound;{{ number_format($product['credit_info']['amount_service']/100, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Total Cost of Credit</th>
                                                <td>&pound;{{ number_format($product['credit_info']['loan_cost']/100, 2) }}</td>
                                            </tr>
                                            {{--<tr>--}}
                                                {{--<th>Total Cost</th>--}}
                                                {{--<td>&pound;{{ number_format($product['credit_info']['total_cost']/100, 2) }}</td>--}}
                                            {{--</tr>--}}
                                            <tr>
                                                <th>Total Amount Payable</th>
                                                <td>&pound;{{ number_format($product['credit_info']['total_repayment']/100, 2) }}</td>
                                            </tr>
                                            </tbody></table>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg btn-block">Continue</button>


                                    {!! Form::hidden('amount', $amount) !!}
                                    {!! Form::hidden('product', $product['id']) !!}
                                    {!! Form::hidden('product_name', $product['name']) !!}
                                    {!! Form::hidden('group', $group['id']) !!}

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
</body>
@endsection
