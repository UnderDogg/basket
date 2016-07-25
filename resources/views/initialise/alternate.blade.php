@extends('master')

@section('page')
<body>
    <div class="container-fluid">
        <h1>Additional Information</h1>
        {!! Form::open(['action' => ['InitialisationController@request', Request::segment(2)], 'class' => 'form-horizontal']) !!}
        <div class="form-group col-xs-12">
            {!! Form::label('reference', 'Reference:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('reference', $input['reference'], ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('description', 'Description:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('description', $input['description'], ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('title', 'Title:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-9">
                <select class="form-control col-xs-12" name="title">
                    <option disabled selected hidden>Please select...</option>
                    <option selected value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Miss">Miss</option>
                    <option value="Ms">Ms</option>
                </select>
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('first_name', 'First Name:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('first_name', 'ddd', ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('last_name', 'Last Name:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('last_name', 'ddd', ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('applicant_email', 'Email:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::email('applicant_email', 'e@e.com', ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('phone_mobile', 'Mobile Phone:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-9">
                {!! Form::text('phone_mobile', null, ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>

        <!-- Previous input values -->
        @foreach($input as $key => $value)
            @if(!is_null($value) && !in_array($value, ['reference', 'description']))
                {!! Form::hidden($key, $value) !!}
            @endif
        @endforeach
        {!! Form::hidden('subject', 'afforditNOW Finance Application') !!}
        {!! Form::hidden('installation', $location->installation->id) !!}

        <div class="form-group col-xs-12">
            @foreach($location->installation->getBitwiseFinanceOffers() as $key => $offer)
                @if(count($bitwise->explode()) == 1)<div class="col-sm-12 col-xs-12">@endif
                @if(count($bitwise->explode()) == 2)<div class="col-sm-6 col-xs-12">@endif
                @if($bitwise->contains($offer['value']))
                    <button type="submit" class="btn btn-success btn-lg btn-block"@if(isset($offer['name'])) name="{!! $offer['name'] !!}" value="true"@endif>{!! $offer['text'] !!}</button>
                @endif
                </div>

            @endforeach
            {{--<div class="col-sm-12 col-md-9">--}}
                {{--{!! Form::text('phone_mobile', null, ['class' => 'form-control col-xs-12']) !!}--}}
            {{--</div>--}}
        </div>

        {!! Form::close() !!}
    </div>
</body>

@endsection

