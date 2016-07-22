@extends('master')

@section('page')
<body>
    <div class="container-fluid">
        {!! Form::open(['action' => ['InitialisationController@request', Request::segment(2)], 'class' => 'form-horizontal']) !!}
        <div class="form-group collapse-form-group col-xs-12">
            {!! Form::label('reference', 'Reference:', ['class' => 'col-sm-12 col-md-2 control-label text-right collapse-form-label']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('reference', $input['reference'], ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
            </div>
        </div>
        <div class="form-group collapse-form-group col-xs-12">
            {!! Form::label('description', 'Description:', ['class' => 'col-sm-12 col-md-2 control-label text-right collapse-form-label']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('description', $input['description'], ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
            </div>
        </div>
        <div class="form-group collapse-form-group col-xs-12">
            {!! Form::label('title', 'Title:', ['class' => 'col-sm-12 col-md-2 control-label text-right collapse-form-label']) !!}
            <div class="col-sm-12 col-md-9">
                <select class="form-control col-xs-12 collapse-form-input" name="title">
                    <option disabled selected hidden>Please select...</option>
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Miss">Miss</option>
                    <option value="Ms">Ms</option>
                </select>
            </div>
        </div>
        <div class="form-group collapse-form-group col-xs-12">
            {!! Form::label('first_name', 'First Name:', ['class' => 'col-sm-12 col-md-2 control-label text-right collapse-form-label']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('first_name', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
            </div>
        </div>
        <div class="form-group collapse-form-group col-xs-12">
            {!! Form::label('last_name', 'Last Name:', ['class' => 'col-sm-12 col-md-2 control-label text-right collapse-form-label']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('last_name', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
            </div>
        </div>
        <div class="form-group collapse-form-group col-xs-12">
            {!! Form::label('applicant_email', 'Email:', ['class' => 'col-sm-12 col-md-2 control-label text-right collapse-form-label']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::email('applicant_email', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
            </div>
        </div>
        <div class="form-group collapse-form-group col-xs-12">
            {!! Form::label('phone_mobile', 'Mobile Phone:', ['class' => 'col-sm-12 col-md-2 control-label text-right collapse-form-label']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('phone_mobile', null, ['class' => 'form-control col-xs-12 collapse-form-input']) !!}
            </div>
        </div>

        <!-- Previous input values -->
        @foreach($input as $key => $value)
            @if(!is_null($value) && !in_array($value, ['reference', 'description']))
                {!! Form::hidden($key, $value) !!}
            @endif
        @endforeach

        <button type="submit" class="btn btn-success btn-lg btn-block" value="true">Do it</button>

        {!! Form::close() !!}
    </div>
</body>

@endsection

