@extends('main')

@section('content')

    <h1>Edit Product</h1>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $installation->name]])
    <p>&nbsp;</p>

    <p id="product_ordering_help">
        Click, drag and drop a product to determine its order when shown in the choose product drop-down list on our checkout page.
    </p>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#product-limits" aria-controls="product-limits" role="tab" data-toggle="tab">Product Limits</a></li>
        <li role="presentation"><a href="#product_ordering" aria-controls="product-ordering" role="tab" data-toggle="tab">Product Order</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="product-limits">
            {!! Form::open(['method' => 'POST','action' => ['ProductConfigurationController@updateProducts', $installation->id], 'class' => 'form-horizontal']) !!}
            <input type = "hidden" name="save" id="save" value="limits" />
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Product</th>
                    <th colspan="4">Defined Deposit Limit</th>
                    <th colspan="2">Retailer Deposit Limit</th>
                </tr>
                <tr>
                    <th>Product ID</th>
                    <th>Full Product Name</th>
                    <th>Min %</th>
                    <th>Max %</th>
                    <th>Min £</th>
                    <th>Max £</th>
                    <th>Min %</th>
                    <th>Max %</th>
                </tr>
                </thead>
                <tbody>
                @if($i = 0) @endif
                @forelse($grouped_products as $group)
                    <tr id="{{$group->getId()}}">
                        <td colspan="8"><strong>{{$group->getName()}}</strong></td>
                    </tr>
                    @forelse($group->getProducts() as $productData)
                        <tr class="{{$productData->getId()}}">
                            <td><code>{{$productData->getId()}}</code></td>
                            <td>{{$productData->getName()}}</td>
                            <td id="min" data-value="{{number_format($productData->getDeposit()->getMinimumPercentage(), 2)}}">{{number_format($productData->getDeposit()->getMinimumPercentage(), 2)}}%</td>
                            <td id="max" data-value="{{number_format($productData->getDeposit()->getMaximumPercentage(), 2)}}">{{number_format($productData->getDeposit()->getMaximumPercentage(), 2)}}%</td>
                            <td>{{'&pound;' . number_format($productData->getDeposit()->getMinimumAmount()/100, 2)}}</td>
                            <td>{{'&pound;' . number_format($productData->getDeposit()->getMaximumAmount()/100, 2)}}</td>
                            @if(($productData->getDeposit()->getMinimumPercentage() == $productData->getDeposit()->getMaximumPercentage()) ||  ($productData->getDeposit()->getMinimumAmount() == $productData->getDeposit()->getMaximumAmount()))
                                <td colspan="2">Fixed deposit</td>
                            @else
                                @if($i++) @endif
                                <td><input type="text" class="form-control" data-fv-digits="true" data-fv-stringlength-max="2" maxlength="2" data-fv-notempty data-fv-between-inclusive="true" min="{{number_format($productData->getDeposit()->getMinimumPercentage(), 0)}}" max="{{$productData->getDeposit()->getMaximumPercentage()}}" name="min-{{$productData->getId()}}" data-fv-lessthan="true" data-fv-lessthan-value="max-{{$productData->getId()}}" value="@if(array_has($limits, $productData->getId())){{number_format($limits[$productData->getId()]['min_deposit_percentage'], 0)}}@else{{number_format($productData->getDeposit()->getMinimumPercentage(), 0)}}@endif"></td>
                                <td><input type="text" class="form-control" data-fv-digits="true" data-fv-stringlength-max="2" maxlength="2" data-fv-notempty data-fv-between-inclusive="true" min="{{number_format($productData->getDeposit()->getMinimumPercentage(), 0)}}" max="{{$productData->getDeposit()->getMaximumPercentage()}}" name="max-{{$productData->getId()}}" data-fv-greaterthan="true" data-fv-greaterthan-value="min-{{$productData->getId()}}" value="@if(array_has($limits, $productData->getId())){{number_format($limits[$productData->getId()]['max_deposit_percentage'], 0)}}@else{{number_format($productData->getDeposit()->getMaximumPercentage(), 0)}}@endif"></td>
                        @endif
                        <tr>
                    @empty
                        <tr>
                            <td colspan="8">There are no editable products for {{$group->getName()}}</td>
                        <tr>
                    @endforelse
                @empty
                    <tr><td colspan="8">There are no editable products to display</td></tr>
                @endforelse
                </tbody>
            </table>
            @if(isset($i) && $i > 0)
                {!! Form::submit('Save Product Limits', ['class' => 'btn btn-info pull-right']) !!}
            @endif
            {!! Form::close() !!}
        </div>

        <div role="tabpanel" class="tab-pane" id="product_ordering">
            {!! Form::open(['method' => 'POST','action' => ['ProductConfigurationController@updateProducts', $installation->id], 'class' => 'form-horizontal']) !!}
            <input type = "hidden" name="product_order" id="product_order" />
            <input type = "hidden" name="save" id="save" value="order" />
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Product</th>
                    <th colspan="3">Terms</th>
                    <th colspan="2">Customer Fees</th>
                    <th colspan="2">Order Range</th>
                    <th colspan="4">Deposit</th>
                    <th colspan="4">Retailer Fees</th>
                    <th colspan="3">Commission</th>
                </tr>
                <tr>
                    <th>Product ID</th>
                    <th>Full Product Name</th>
                    <th>Holiday</th>
                    <th>Payments</th>
                    <th>Interest</th>
                    <th>Service</th>
                    <th>Settlement</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>Min %</th>
                    <th>Max %</th>
                    <th>Min £</th>
                    <th>Max £</th>
                    <th>%</th>
                    <th>Min £</th>
                    <th>Max £</th>
                    <th>Cancellation</th>
                    <th>Min %</th>
                    <th>Min</th>
                    <th>Max</th>
                </tr>
                </thead>
                <tbody id="sortable_tbody">

                @forelse($products as $productData)
                    @if($i = 0) @endif
                    <tr id="{{$productData->getId()}}">
                        <td><code>{{$productData->getId()}}</code></td>
                        <td>{{$productData->getName()}}</td>
                        <td>{{$productData->getHolidays()}}</td>
                        <td>{{$productData->getPayments()}}</td>
                        <td>{{number_format($productData->getPerAnnumInterestRate(), 1)}}%</td>
                        <td>{{'&pound;' . number_format($productData->getCustomerServiceFee()/100, 2)}}</td>
                        <td>{{'&pound;' . number_format($productData->getCustomerSettlementFee()/100, 2)}}</td>
                        <td>{{'&pound;' . number_format($productData->getOrder()->getMinimumAmount()/100, 2)}}</td>
                        <td>{{'&pound;' . number_format($productData->getOrder()->getMaximumAmount()/100, 2)}}</td>
                        <td>{{number_format($productData->getDeposit()->getMinimumPercentage(), 2)}}%</td>
                        <td>{{number_format($productData->getDeposit()->getMaximumPercentage(), 2)}}%</td>
                        <td>{{'&pound;' . number_format($productData->getDeposit()->getMinimumAmount()/100, 2)}}</td>
                        <td>{{'&pound;' . number_format($productData->getDeposit()->getMaximumAmount()/100, 2)}}</td>
                        <td>{{number_format($productData->getMerchantFees()->getPercentage(), 2)}}%</td>
                        <td>{{'&pound;' . number_format($productData->getMerchantFees()->getMinimumAmount()/100, 2)}}</td>
                        <td>{{'&pound;' . number_format($productData->getMerchantFees()->getMaximumAmount()/100, 2)}}</td>
                        <td>{{'&pound;' . number_format($productData->getMerchantFees()->getCancellation()/100, 2)}}</td>
                        <td>{{number_format($productData->getMerchantCommission()->getPercentage(), 2)}}%</td>
                        <td>{{'&pound;' . number_format($productData->getMerchantCommission()->getMinimumAmount()/100, 2)}}</td>
                        <td>{{'&pound;' . number_format($productData->getMerchantCommission()->getMaximumAmount()/100, 2)}}</td>
                    <tr>
                    @if($i++) @endif
                @empty
                    <tr>There are no products to display</tr>
                @endforelse
                </tbody>
            </table>
            @if(isset($i) && $i > 0)
                {!! Form::submit('Save Product Order', ['class' => 'btn btn-info pull-right', 'onClick' => 'saveOrder();']) !!}
            @endif
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.form-horizontal').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                err: {
                    container: 'tooltip'
                },
                row: {
                    selector: 'td'
                }
            }).on('success.validator.fv', function(e, data) {
                var type = data.field.split('-')[0];
                var product = data.field.split('-').slice(1).join('-');
                var opposite = (type == 'min') ? 'max' : 'min';

                if (data.fv.isValidField(type + '-' + product) && !data.fv.isValidField(opposite + '-' + product)) {
                    data.fv.revalidateField(opposite + '-' + product);
                }
            });
        });

        $('#sortable_tbody').sortable();

        function saveOrder() {
            var products = new Array();
            $('tbody#sortable_tbody tr').each(function() {
                if ($(this).attr("id")) {
                    products.push($(this).attr("id"));
                }
            });
            document.getElementById("product_order").value = products;
        }
    </script>
@endsection
