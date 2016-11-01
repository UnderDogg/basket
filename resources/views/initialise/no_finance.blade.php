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
            <div class="col-sm-8 col-sm-offset-2">
                <h2>Unfortunately we are unable to create an application link for this email address at this time</h2>
                <div class="alert alert-info" role="alert">
                    There are a number of reasons you may receive this message. Please ask the customer to contact our
                    Customer Support team on <a href="tel:03333444226">03333 444 226</a> or they can email
                    <a href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;tf=1&amp;to=retailer@afforditnow.com" target="_blank">retailer@afforditnow.com</a>
                    and we will be able to assist in resolving this issue.
                </div>
                <a href="/" class="btn btn-default pull-right">Return to Dashboard</a>

            </div>
        </div>
    </div>
    </body>

@endsection

@section('stylesheets')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="{!! Bust::cache('/formvalidation/dist/js/formValidation.min.js') !!}"></script>
    <script src="{!! Bust::cache('/formvalidation/dist/js/framework/bootstrap.min.js') !!}"></script>

    <script src="{!! asset(Bust::cache('/js/fv.js')) !!}"></script>
@endsection
