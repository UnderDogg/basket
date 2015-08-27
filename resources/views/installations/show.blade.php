@extends('master')

@section('content')

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        <div class="btn-group pull-right">
            <a href="{{Request::url()}}/edit" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Edit</a>
            <a href="/locations/?installation_id={{Request::segment(2)}}" class="btn btn-default"><span class="glyphicon glyphicon-map-marker"></span> Locations</a>
            <a href="/merchants/{{$installations->merchant_id}}" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> Merchant</a>
        </div>
    </h2>
    @include('includes.page.breadcrumb', ['over' => [1  => $installations->name], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#part1">Installation Details</a></li>
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

    </div>
@endsection
