<div role="tabpanel" class="tab-pane active" id="prod-FF">
    {!! Form::open(['action' => ['InitialisationController@request', $location->id], 'class' => 'initialiseForm']) !!}
    <h2>Flexible Finance</h2>
    <div class="form-group container-fluid">
        <div class="row text-center">

            <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 well flex-slider-container">
                <h2>Holiday in Months</h2>
                <div id="slider-range-holiday"></div>
                <br><br>
                <h2>Term in Months</h2>
                <div id="slider-range-term"></div>
            </div>

            <div class="col-md-12 credit-info" data-product="FF" style="display:none;">
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
            <div class="row">
                @if($bitwise->contains(2) && count($bitwise->explode()) > 1)<div class="col-sm-6 col-xs-12">@else <div class="col-sm-12 col-xs-12">@endif
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

                    {!! Form::hidden('amount', $amount, ['data-product' => 'FF']) !!}
                    {!! Form::hidden('product', 'AIN1-3', ['data-product' => 'FF', 'data-field' => 'product']) !!}
                    {!! Form::hidden('group', 'FF', ['data-product' => 'FF', 'data-field' => 'group']) !!}
                    {!! Form::hidden('token', csrf_token(), ['data-product' => 'FF', 'data-field' => 'token']) !!}
                    {!! Form::hidden('pay_today', 0, ['data-product' => 'FF', 'class' => 'pay_today', 'data-calcfield' => 'deposit_amount|amount_service']) !!}
                    {!! Form::hidden('reference', $reference, ['data-product' => 'FF']) !!}
                    {!! Form::hidden('description', 'Goods & Services', ['data-product' => 'FF']) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>