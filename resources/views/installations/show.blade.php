@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}
        @include('includes.page.show_details_button_group', ['id'=>$installations->id,'edit'=>true,'sync'=>true])
    </h2>
    @include('includes.page.breadcrumb', ['override2'=>$installations->name])

    <div id="basketTabs">
        <ul class="nav nav-tabs">
            <li role="presentation" class="tabbutton active"><a href="#fragment-1"><h5>Installation Details</h5></a></li>
        </ul>
        <div class="col-xs-12">&nbsp;</div>
        <hr>
        {{--FIRST PANEL: ROLE DETAILS--}}

        <div id="fragment-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Key information</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Name: </strong> {{ $installations->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Active Status: </strong>
                            @if( $installations->active == 0 )
                                <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i></span>
                            @elseif( $installations->active == 1 )
                                <span class="label label-success"><i class="glyphicon glyphicon-ok"></i></span>
                            @endif
                        </li>
                        <li class="list-group-item">
                            <strong>Linked Status: </strong>
                            @if( $installations->linked == 0 )
                                <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i></span>
                            @elseif( $installations->linked == 1 )
                                <span class="label label-success"><i class="glyphicon glyphicon-ok"></i></span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">External Information</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Installation ID: </strong> {{ $installations->ext_id }}
                        </li>
                        <li class="list-group-item">
                            <strong>Installation Name: </strong> {{ $installations->ext_name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Return URL: </strong> {{ $installations->ext_return_url }}
                        </li>
                        <li class="list-group-item">
                            <strong>Notification URL: </strong> {{ $installations->ext_notification_url }}
                        </li>
                        <li class="list-group-item">
                            <strong>Default Product: </strong> {{ $installations->ext_default_product }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
