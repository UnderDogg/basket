@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        @include('includes.page.show_details_button_group', ['id'=>$installations->id,'edit'=>true,'sync'=>true])
    </h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => $installations->name]])

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

        </div>

    </div>
@endsection
