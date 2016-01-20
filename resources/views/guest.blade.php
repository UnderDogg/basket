@extends('master')

@section('page')
    <body class="center-login">
        @if(env('ENV_BANNER', false))
            @include('env-banner')
        @endif
        <div class="form-group panel-heading center-box">
            <form method="POST" @if(isset($action))action="{{$action}}" @endif id="loginForm">
                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-xs-8 col-sm-6 col-md-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4">
                        <div class="center-logo">
                            {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                        </div>
                        <br/>
                        <div></div>
                        @if (session('status')) <div class="alert alert-success alert-dismissible" data-dismiss="alert">
                            <button type="button" class="close">&times;</button>
                            {{ session('status') }}
                            Please check your account for this email and follow the instructions provided.
                        </div> @endif
                        @if($errors->any()) <div class="alert alert-danger alert-dismissible" data-dismiss="alert">
                            <button type="button" class="close">&times;</button>
                            {{$errors->first()}}
                            Please ensure that there are no spelling errors. If problems persist, please contact
                            the administrator.
                        </div> @endif
                        @yield('content')
                    </div>
                </div>
            </form>
        </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src={!! asset('/formvalidation/dist/js/formValidation.min.js') !!}></script>
    <script src={!! asset('/formvalidation/dist/js/framework/bootstrap.min.js') !!}></script>
    @yield('scripts')
    </body>
@endsection
