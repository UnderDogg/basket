@extends('master')

@section('page')

    <body>
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
    </body>
@endsection
