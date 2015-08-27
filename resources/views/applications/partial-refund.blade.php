@extends('main')

@section('content')

    <h2>Partial Refund</h2>
    @include('includes.page.breadcrumb', ['over' => [1 => $application->installation->name], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])

    <p>&nbsp;</p>
    {!! Form::open( ['method'=>'post', 'class' => 'form-horizontal'] ) !!}
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Key Information</strong></div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                @if(isset($application->ext_id))
                    <dt>Order ID</dt>
                    <dd>{{$application->ext_id}}</dd>
                @endif
                @if(isset($application->ext_order_reference))
                    <dt>Reference</dt>
                    <dd>{{$application->ext_order_reference}}</dd>
                @endif
                @if(isset($application->ext_order_amount))
                    <dt>Order Amount</dt>
                    <dd>{{'&pound;' . number_format($application->ext_order_amount/100,2)}}</dd>
                @endif
                @if(isset($application->ext_order_description))
                    <dt>Order Description</dt>
                    <dd>{{$application->ext_order_description}}</dd>
                @endif
                @if(isset($application->ext_fulfilment_location))
                    <dt>Fulfilment Location</dt>
                    <dd>{{$application->ext_fulfilment_location}}</dd>
                @endif
                @if(isset($application->ext_customer_title))
                    <dt>Customer Title</dt>
                    <dd>{{$application->ext_customer_title}}</dd>
                @endif
                @if(isset($application->ext_customer_first_name))
                    <dt>Customer First Name</dt>
                    <dd>{{$application->ext_customer_first_name}}</dd>
                @endif
                @if(isset($application->ext_customer_last_name))
                    <dt>Customer Surname</dt>
                    <dd>{{$application->ext_customer_last_name}}</dd>
                @endif
            </dl>
        </div>
    </div>
    <div class="alert alert-warning">
        <p>Please confirm that you would like to request a partial refund of this application. Please note that you will not be able to reverse this later.</p>
    </div>
    <div class="container">

        <div class="form-group">
            {!! Form::label('refund_amount', 'Refund Amount') !!}
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {!! Form::text('refund_amount', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('effective_date', 'Effective Date') !!}
            <div class="input-group">
                <input name="effective_date" type="text" class="form-control" id="datepicker_to">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Description') !!}
            {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
        </div>

        <p>&nbsp;</p>
        <div class="form-group pull-right">
            {!! Form::submit('Request', [
            'class' => 'btn btn-success',
            'name' => 'confirmRequest'
            ]) !!}
            <a href="{{Request::server('HTTP_REFERER')}}" class="btn btn-info">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}

@endsection
