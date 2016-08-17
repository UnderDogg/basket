@extends('master')

@section('page')

    <body>
    @include('includes.message.action_response')
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
            </div>
            <br/>
            <div class="col-md-offset-3 col-md-6 well">
                <h1>Thank you</h1>
                <p>Please hand the device back to the Sales assistant.</p>
            </div>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{!! Bust::cache('/js/main.js') !!}"></script>
    </body>
@endsection
