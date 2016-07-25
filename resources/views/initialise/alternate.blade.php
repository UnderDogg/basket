@extends('master')

@section('page')
<body>
@if(env('ENV_BANNER', false))
    @include('env-banner')
@endif
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="pull-left">
                    <a href="/">
                        {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    @if($location->installation->custom_logo_url)
                        {!! HTML::image($location->installation->custom_logo_url, 'logo') !!}
                    @endif
                </div>
            </div>
        </div>
        <br/>
        <h1>Additional Information</h1>
        <br/>
        {!! Form::open(['action' => ['InitialisationController@request', Request::segment(2)], 'class' => 'form-horizontal']) !!}
        <div class="form-group col-xs-12">
            {!! Form::label('reference', 'Reference:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-10">
                {!! Form::text('reference', $input['reference'], ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('description', 'Description:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-10">
                {!! Form::text('description', $input['description'], ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('title', 'Title:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-10">
                <select class="form-control col-xs-12" name="title">
                    <option disabled selected hidden>Please select...</option>
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Miss">Miss</option>
                    <option value="Ms">Ms</option>
                </select>
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('first_name', 'First Name:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-10">
                {!! Form::text('first_name', null, ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('last_name', 'Last Name:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-10">
                {!! Form::text('last_name', null, ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('applicant_email', 'Email:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-10">
                {!! Form::email('applicant_email', null, ['class' => 'form-control col-xs-12']) !!}
            </div>
        </div>
        <div class="form-group col-xs-12">
            {!! Form::label('phone_mobile', '(Optional) Mobile Phone:', ['class' => 'col-sm-12 col-md-2 control-label text-right']) !!}
            <div class="col-sm-12 col-md-10">
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
                @if($bitwise->contains($offer['value']))
                    @if(count($bitwise->explode()) == 1)<div class="col-sm-12 col-xs-12">@endif
                    @if(count($bitwise->explode()) == 2)<div class="col-sm-6 col-xs-12">@endif
                        <button type="submit" class="btn btn-success btn-lg btn-block btn-bottom-margin"@if(isset($offer['name'])) name="{!! $offer['name'] !!}" value="true"@endif>{!! $offer['text'] !!}</button>
                    </div>
                @endif

            @endforeach
        </div>

        {!! Form::close() !!}
    </div>
</body>

@endsection

