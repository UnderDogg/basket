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
                    {{--@if($location->installation->custom_logo_url)--}}
                        {{--{!! HTML::image($location->installation->custom_logo_url, 'logo') !!}--}}
                    {{--@endif--}}
                </div>
            </div>
        </div>
        <br/>
        @include('includes.message.action_response')
        <h1>Profile Information</h1>
        @include('initialise.profile.personal', ['validation' => true])
    </div>
</div>

</body>

@endsection

@section('stylesheets')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="{!! asset(Bust::cache('/formvalidation/dist/js/formValidation.min.js')) !!}"></script>
    <script src="{!! asset(Bust::cache('/formvalidation/dist/js/framework/bootstrap.min.js')) !!}"></script>
@endsection
