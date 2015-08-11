@extends('master')

@section('content')

    {{-- OVERLAY MESSAGES --}}
    @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])

    <h2>View Partial Refund</h2>
    @include('includes.page.breadcrumb', ['crumbs' => Request::segments(), 'over' => [1  => '#'.$partialRefund->id]])

    <div id="basketTabs">
        <ul class="nav nav-tabs">
            <li role="presentation" class="tabbutton active"><a href="#fragment-1"><h5>Partial Refund Details</h5></a></li>
        </ul>
        <div class="col-xs-12">&nbsp;</div>
        <hr>
        <div id="fragment-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">External Information</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Application: </strong> {{ $partialRefund->application }}
                        </li>
                        <li class="list-group-item">
                            <strong>Status: </strong> {{ $partialRefund->status }}
                        </li>
                        <li class="list-group-item">
                            <strong>Refund Amount: </strong> {{ money_format('%.2n', $partialRefund->refund_amount/100) }}
                        </li>
                        <li class="list-group-item">
                            <strong>Effective Date: </strong> {{ DateTime::createFromFormat('Y-m-d', $partialRefund->effective_date)->format('d/m/Y') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Requested Date: </strong> {{ DateTime::createFromFormat('Y-m-d', $partialRefund->requested_date)->format('d/m/Y') }}
                        </li>
                        <li class="list-group-item">
                            <strong>Description: </strong> {{ $partialRefund->description }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
