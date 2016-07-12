@extends('main')

@section('content')
    <h1>View Installations
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/edit" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Edit</a>
            <a href="/locations/?installation_id={{Request::segment(2)}}" class="btn btn-default"><span class="glyphicon glyphicon-map-marker"></span> Locations</a>
            <a href="/merchants/{{$installations->merchant_id}}" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> Merchant</a>
        </div>
    </h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $installations->name], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#part1">Installation Details</a></li>
        <li><a data-toggle="tab" href="#part2">Finance Product Details</a></li>
    </ul>
    <div class="tab-content">
        <div id="part1" class="tab-pane fade in active">
            <br/>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Key Information</strong></div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Name</dt>
                        <dd>{!! $installations->name !!}</dd>
                        <dt>Active Status</dt>
                        <dd>
                            @if( $installations->active == 0 )
                                <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i> Inactive</span>
                            @elseif( $installations->active == 1 )
                                <span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Active</span>
                            @endif
                        </dd>
                        <dt>Linked Status</dt>
                        <dd>
                            @if( $installations->linked == 0 )
                                <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i> Unlinked</span>
                            @elseif( $installations->linked == 1 )
                                <span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Linked</span>
                            @endif
                        </dd>
                        @foreach($installations->getBitwiseFinanceOffers() as $key => $offer)
                            <dt>Finance Offer: {{ucwords(str_replace('_',' ', $key))}}</dt>
                            <dd>
                                @if( $offer['active'] == false )
                                    <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i> Inactive</span>
                                @elseif( $offer['active'] == true )
                                    <span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Active</span>
                                @endif
                            </dd>
                        @endforeach
                    </dl>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>External Information</strong></div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Installation ID</dt>
                        <dd>{!! $installations->ext_id !!}</dd>
                        <dt>Installation Name</dt>
                        <dd>{!! $installations->ext_name !!}</dd>
                        <dt>Return URL</dt>
                        <dd>{!! $installations->ext_return_url !!}</dd>
                        <dt>Notification URL</dt>
                        <dd>{!! $installations->ext_notification_url !!}</dd>
                        <dt>Default Product</dt>
                        <dd>{!! $installations->ext_default_product !!}</dd>
                    </dl>
                </div>
            </div>

            @if($installations->location_instruction)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Location Additional Instructions</h3>
                </div>
                <div class="panel-body">
                    {!! $installations->getLocationInstructionAsHtml() !!}
                </div>
            </div>
            @endif

            @if($installations->default_email_footer)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Default Email Footer</h3>
                </div>
                <div class="panel-body">
                    {!! $installations->getDefaultEmailFooterAsHtml() !!}
                </div>
            </div>
            @endif

            @if($installations->disclosure)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">In Store Disclosure</h3>
                    </div>
                    <div class="panel-body">
                        {!! $installations->getDisclosureAsHtml() !!}
                    </div>
                </div>
            @endif

            @if($installations->custom_logo_url)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Custom Logo</h3>
                    </div>
                    <div class="panel-body">
                        <img src="{{ $installations->custom_logo_url }}" />
                    </div>
                </div>
            @endif

        </div>
        <div id="part2" class="tab-pane fade in">
            <br/>
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
                <tbody>
                    @forelse($products as $group)
                        <tr class="clickable" data-toggle="collapse" id="{{$group->getId()}}" data-target=".{{$group->getId()}}collapsed">
                            <td colspan="20"><span class="glyphicon glyphicon-plus"></span><span class="glyphicon glyphicon-minus hidden"></span> {{$group->getName()}}</td>
                        </tr>
                        @forelse($group->getProducts() as $productData)
                            <tr class="collapse out {{$group->getId()}}collapsed">
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
                        @empty
                            <tr>
                                <td>There are no products for {{$group->getName()}}</td>
                            <tr>
                        @endforelse
                    @empty
                        <tr>There are no products to display</tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('tr.clickable').on('click', function () {
            if()
            $(this).find('span').each(function() {
                if($(this).hasClass('hidden')) {
                    $(this).removeClass('hidden');
                } else {
                    $(this).addClass('hidden');
                }
            });
        });
    </script>
@endsection
