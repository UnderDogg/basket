@extends('master')

@section('page')
<body>
@if(env('ENV_BANNER', false))
    @include('env-banner')
@endif
<div class="loading"></div>
{!! Form::hidden('location', isset($location) ? $location->id : null) !!}
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
        @include('includes.message.action_response', ['messages' => $messages, 'errors' => $errors])
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <h1>Profile Information</h1>
                </hr>
                <p>
                    Please fill in as much information as you can before sending the application email. Once you have
                    filled out all the details that you can, click 'Continue and Send Email' below to send a link to the customer.
                </p>
            </div>
            @if(!isset($user))
                @include('initialise.profile.personal', ['validation' => true])
            @else
                @include('initialise.profile.address', ['validation' => true])
                @include('initialise.profile.employment', ['validation' => true])
                @include('initialise.profile.financial', ['validation' => true])
            @endif
        </div>
    </div>
</div>

</body>

@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{!! Bust::cache('/css/sweetalert.css') !!}">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="{!! asset(Bust::cache('/formvalidation/dist/js/formValidation.min.js')) !!}"></script>
    <script src="{!! asset(Bust::cache('/formvalidation/dist/js/framework/bootstrap.min.js')) !!}"></script>
    <script src="{!! Bust::cache('/js/sweetalert.min.js') !!}"></script>
    <script src="{!! asset(Bust::cache('/js/profile.js')) !!}"></script>
@endsection
