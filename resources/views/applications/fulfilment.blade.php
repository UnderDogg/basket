@extends('master')

@section('content')

    <h2>Fulfil Application</h2>

    @include('includes.page.breadcrumb', ['crumbs' => Request::segments()])

        <p>&nbsp;</p>
        {!! Form::open( ['method'=>'post'] ) !!}
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
        <div class="alert alert-info">
            <p>Please confirm that you would like to Fulfil this
                @if(isset($application->ext_customer_first_name) && isset($application->ext_customer_last_name))
                    application for <strong>{{$application->ext_customer_first_name}} {{$application->ext_customer_last_name}}</strong>.
                @else
                    application.
                @endif
                    You will not be able to reverse this later.
            </p>
        </div>
        <div class="pull-right">
            {!! Form::submit('Fulfil', [
                        'class' => 'btn btn-success',
                        'name' => 'confirmDelete'
                        ]) !!}
            <a href="{{Request::server('HTTP_REFERER')}}" class="btn btn-info">Cancel</a>
        </div>
        {!! Form::close() !!}

    {{--@endif--}}

@endsection
