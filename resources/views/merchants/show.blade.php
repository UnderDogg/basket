@extends('main')

@section('content')

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/edit" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Edit</a>
            <a href="{{Request::url()}}/synchronise" class="btn btn-default"><span class="glyphicon glyphicon-refresh"></span> Sync</a>
            <a href="/installations/?merchant_id={{Request::segment(2)}}" class="btn btn-default"><span class="glyphicon glyphicon-hdd"></span> Installations</a>
            <a href="{{Request::url()}}/ips" class="btn btn-default"><span class="glyphicon glyphicon-list-alt"></span> View IP's</a>
        </div>
    </h2>
    @include('includes.page.breadcrumb', ['over' => [1  => $merchants->name]])
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#part1">Merchant Details</a></li>
    </ul>
    <div class="tab-content">
        <div id="part1" class="tab-pane fade in active">
            <br/>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Key Information</strong></div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Merchant Name</dt>
                        <dd>{!! $merchants->name!!}</dd>
                        <dt>Linked Status</dt>
                        <dd>
                            @if( $merchants->linked == 0 )
                                <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i> Unlinked</span>
                            @elseif( $merchants->linked == 1 )
                                <span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Linked</span>
                            @endif
                        </dd>
                        <dt>Active Status</dt>
                        <dd>
                            @if( $merchants->active == 0 )
                                <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i> Inactive</span>
                            @elseif( $merchants->active == 1 )
                                <span class="label label-success"><i class="glyphicon glyphicon-ok"></i> Active</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>External Information</strong></div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Company Name</dt>
                        <dd>{!! $merchants->ext_company_name !!}</dd>
                        <dt>Abode</dt>
                        <dd>{!! json_decode($merchants->ext_address, true)['abode'] !!}</dd>
                        <dt>Building Name</dt>
                        <dd>{!! json_decode($merchants->ext_address, true)['building_name'] !!}</dd>
                        <dt>Building Number</dt>
                        <dd>{!! json_decode($merchants->ext_address, true)['building_number'] !!}</dd>
                        <dt>Street</dt>
                        <dd>{!! json_decode($merchants->ext_address, true)['street'] !!}</dd>
                        <dt>Locality</dt>
                        <dd>{!! json_decode($merchants->ext_address, true)['locality'] !!}</dd>
                        <dt>Town</dt>
                        <dd>{!! json_decode($merchants->ext_address, true)['town'] !!}</dd>
                        <dt>Postcode</dt>
                        <dd>{!! json_decode($merchants->ext_address, true)['postcode'] !!}</dd>
                        <dt>Processing Days</dt>
                        <dd>{!! $merchants->ext_processing_days !!}</dd>
                        <dt>Min Amount Settled</dt>
                        <dd>{!! $merchants->ext_minimum_amount_settled !!}</dd>
                        <dt>Address On Agreement</dt>
                        <dd>{!! $merchants->ext_address_on_agreements !!}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
