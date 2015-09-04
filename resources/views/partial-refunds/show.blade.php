@extends('main')

@section('content')

    <h1>
        View Partial Refund
        <div class="btn-group pull-right">
            <a href="/installations/{{$installation->installation_id}}/applications/{{$installation->id}}" class="btn btn-default"> View Application </a>
        </div>
    </h1>
    @include('includes.page.breadcrumb', ['over' => [1  => $partialRefund->id], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#part1">Application Details</a></li>
    </ul>
    <div class="tab-content">
        <div id="part1" class="tab-pane fade in active">
            <br/>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Key Information</strong></div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Application</dt>
                        <dd>{{ $partialRefund->application }}</dd>
                        <dt>Status</dt>
                        <dd>{{ ucwords($partialRefund->status) }}</dd>
                        <dt>Refund Amount</dt>
                        <dd>{{ money_format('%.2n', $partialRefund->refund_amount/100) }}</dd>
                        <dt>Effective Date</dt>
                        <dd>{{ DateTime::createFromFormat('Y-m-d', $partialRefund->effective_date)->format('d/m/Y') }}</dd>
                        <dt>Requested Date</dt>
                        <dd>{{ DateTime::createFromFormat('Y-m-d', $partialRefund->requested_date)->format('d/m/Y') }}</dd>
                        <dt>Description</dt>
                        <dd>{{ $partialRefund->description }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
