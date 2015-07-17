@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>{{ Str::upper(' view ' . str_singular(Request::segment(1))) }}</h2>
    @include('includes.page.breadcrumb', ['override2'=>$merchants->name])

    <div id="basketTabs">
        <ul class="nav nav-tabs">
            <li role="presentation" class="tabbutton active"><a href="#fragment-1"><h5>Merchant Details</h5></a></li>
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
                    @if($merchants !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Merchant Name: </strong> {{ $merchants->name  }}
                            </li>
                            <li class="list-group-item">
                                <strong>Linked Status: </strong>
                                @if( $merchants->linked == 0 )
                                    <span class="label label-danger"><i class="glyphicon glyphicon-remove"></i></span>
                                @elseif( $merchants->linked == 1 )
                                    <span class="label label-success"><i class="glyphicon glyphicon-ok"></i></span>
                                @endif
                            </li>
                        </ul>
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">External Information</h3>
                </div>
                <div class="panel-body">
                    @if($merchants !== null)
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Company Name: </strong> {{ $merchants->ext_company_name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Company Address: </strong> {{ $merchants->ext_address }}
                            </li>
                            <li class="list-group-item">
                                <strong>Processing Days: </strong> {{ $merchants->ext_processing_days }}
                            </li>
                            <li class="list-group-item">
                                <strong>Minimum Amount Settled: </strong> {{ $merchants->ext_minimum_amount_settled }}
                            </li>
                            <li class="list-group-item">
                                <strong>Address On Agreements: </strong> {{ $merchants->ext_address_on_agreements }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
