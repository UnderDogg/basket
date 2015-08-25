@extends('master')

@section('content')
<body class="center-login">
    <div class="form-group panel-heading center-box">
        <form method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-xs-8 col-sm-6 col-md-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4">
                    <div class="center-logo">
                        {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                    </div>
                    <br/>
                    <div></div>
                    <div class="inner-addon left-addon">
                        <i class="glyphicon glyphicon-user"></i>
                        <input name="email" type="email" class="form-control" id="inputEmail3" value="{{ old('email') }}" aria-describedby="basic-addon3" placeholder="Email address">
                    </div>
                    <br/>
                    <div class="inner-addon left-addon right-addon">
                        <a href="/password/email">Forgot?</a>
                        <i class="glyphicon glyphicon-asterisk"></i>
                        <input class="form-control" type="password" name="password" aria-describedby="basic-addon2" placeholder="Password">
                    </div>
                    <br/>
                    <div>
                        <button type="submit" class="btn btn-info form-control">
                            Sign In
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
@endsection
