@extends('main')

@section('content')

    <h1>Amend Order</h1>
    @include('includes.page.breadcrumb', ['over' => [1 => $application->installation->name], 'permission' => [0 => Auth::user()->can('merchants-view'), 1 => Auth::user()->can('merchants-view')]])

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
        <p>Please enter the amended order details, and confirm that you want to amend this order</p>
    </div>
    <div class="container-fluid">
        <div class="form-group">
            {!! Form::label('amount', 'Payment Amount') !!}
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {!! Form::text('amount', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Description') !!}
            {!! Form::text('description', (isset($application->ext_order_description) ? $application->ext_order_description : ''), ['class' => 'form-control', 'placeholder' => 'Description']) !!}
        </div>
        <div class="form-group pull-right">
            {!! Form::submit('Amend', ['class' => 'btn btn-success']) !!}
            <a href="{{Request::server('HTTP_REFERER')}}" class="btn btn-info">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}

@endsection
