@extends('master')

@section('content')
    <h1>Confirm Application</h1>

    {!! Form::open(['action' => ['InitialisationController@request', $location->id], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <label class="col-sm-2 control-label">Order Amount</label>
        <div class="col-sm-4">
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {!! Form::text('amount_order', number_format($amount/100,2), ['class' => 'form-control', 'readonly' => true]) !!}
                {!! Form::hidden('amount', $amount) !!}
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Finance Option</label>
        <div class="col-sm-4">
            {!! Form::text('product_name', $product_name, ['class' => 'form-control', 'readonly' => true]) !!}
            {!! Form::hidden('product', $product) !!}
            {!! Form::hidden('group', $group) !!}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Order Reference</label>
        <div class="col-sm-4">
            {!! Form::text('reference', $reference, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Order Description</label>
        <div class="col-sm-4">
            {!! Form::text('description', 'Goods & Services', ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Location name</label>
        <div class="col-sm-4">
            {!! Form::text('location_name', $location->name, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success">Apply Now</button>
        </div>
    </div>

    {!! Form::close() !!}
@endsection
