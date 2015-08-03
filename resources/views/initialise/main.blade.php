@extends('master')

@section('content')

    <h1>Make Application</h1>


    <div class="col-md-12">

        <div class="col-md-12 well">
            {!! Form::open(['class' => 'form-inline']) !!}
            <div class="form-group">
                <label>Price</label>

                <div class="input-group">
                    <div class="input-group-addon">£</div>
                    {!! Form::text('amount', isset($amount)?number_format($amount/100,2):null, ['class' => 'form-control', 'maxlength' => 10]) !!}
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Show Finance Ooptions</button>
            {!! Form::close() !!}
        </div>

        @if(isset($options))

            @if(count($options) > 0)
                <ul class="nav nav-tabs" role="tablist">
                @foreach($options as $k => $group)

                    @foreach($group['products'] as $l => $product)

                            <li role="presentation"{{ ($k == 0 && $l == 0)?' class=active':'' }}><a href="#prod-{{$product['id']}}" aria-controls="prod-{{$product['id']}}" role="tab" data-toggle="tab">{{$product['name']}}</a></li>

                    @endforeach

                @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($options as $k => $group)

                        @foreach($group['products'] as $l => $product)

                            <div role="tabpanel" class="tab-pane{{ ($k == 0 && $l == 0)?' active':'' }}" id="prod-{{$product['id']}}">
                                {!! Form::open(['action' => ['InitialisationController@confirm', $location]]) !!}
                                    <h2>{{$product['name']}}</h2>
                                    <div class="form-group">
                                        <div class="row container text-center">

                                            <div class="col-md-3 col-xs-6" style="background-color: #29abe1; color: white;">
                                                <h2 id="loan-amount">£{{ number_format($product['credit_info']['loan_amount']/100, 2) }}</h2> <p>loan amount</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6" style="background-color: #39b549; color: white;">
                                                <h2>£{{ number_format($product['credit_info']['payment_regular']/100, 2) }}</h2> <p>monthly payment</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6" style="background-color: #aaa; color: white;">
                                                <h2>{{ $product['payments'] }}</h2> <p>payments</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6" style="background-color: #bbb; color: white;">
                                                <h2>{{ number_format($product['credit_info']['apr'], 2) }}%</h2> <p>APR</p>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <table class="table table-condensed" style="font-size: 0.8em;">
                                            <tbody><tr>
                                                <th>Payment Starts</th>
                                                <td>{{ $product['credit_info']['payment_start_nice'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Interest Rate (Fixed)</th>
                                                <td>{{ number_format($product['credit_info']['offered_rate'], 2) }}%</td>
                                            </tr>
                                            <tr>
                                                <th>Deposit</th>
                                                <td>£{{ number_format($product['credit_info']['deposit_amount']/100, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Minimum Deposit</th>
                                                <td>£{{ number_format($product['credit_info']['deposit_range']['minimum_amount']/100, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Maximum Deposit</th>
                                                <td>£{{ number_format($product['credit_info']['deposit_range']['maximum_amount']/100, 2) }}</td>
                                            </tr>
                                            </tbody></table>
                                    </div>
                                    <div class="col-xs-6">
                                        <table class="table table-condensed" style="font-size: 0.8em;">
                                            <tbody>
                                            <tr>
                                                <th>Payment Final</th>
                                                <td>£{{ number_format($product['credit_info']['payment_final']/100, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Service Payment</th>
                                                <td>£{{ number_format($product['credit_info']['amount_service']/100, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Cost of Credit</th>
                                                <td>£{{ number_format($product['credit_info']['loan_cost']/100, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Cost</th>
                                                <td>£{{ number_format($product['credit_info']['total_cost']/100, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount Payable</th>
                                                <td>£{{ number_format($product['credit_info']['total_repayment']/100, 2) }}</td>
                                            </tr>
                                            </tbody></table>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg btn-block">Apply Now</button>


                                    {!! Form::hidden('amount', $amount) !!}
                                    {!! Form::hidden('product', $product['id']) !!}
                                    {!! Form::hidden('group', $group['id']) !!}

                                {!! Form::close() !!}
                            </div>

                    @endforeach

                @endforeach
            @else
                <div class="alert alert-warning" role="alert">No available products for this amount!</div>
            @endif

        @endif

    </div>

@endsection
