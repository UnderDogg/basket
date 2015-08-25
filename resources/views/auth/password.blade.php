@extends('master')

@section('content')
<body style="height: 100vh;">
<div class="form-group panel-heading center-box">
    <form method="POST" action="/password/email">
        {!! csrf_field() !!}

        <div class="row">
            <div class="col-xs-8 col-sm-6 col-md-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4">
                <div class="center-logo">
                    {!! HTML::image('image/ain-logo-standard-medium.svg', 'afforditNOW') !!}
                </div>
                {{-- START OUTPUT RESPONSE --}}
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

                {{-- END OUTPUT RESPONSE --}}
                <h5 style="color: #666666">
                    <span aria-hidden="true">Forgot your password?</span>
                </h5>

                <div class="alert alert-info more_info_box" role="alert" style="display: none">

                    <button type="button" class="close more_info_close">&times;</button>
                    <h4>Email Address</h4>
                    Please provide the email address that is used for your account.  We will then send you an email
                    to that address containing further instructions for, safely and securely, resetting your
                    password.

                </div>
                <div class="inner-addon left-addon">
                    <i class="glyphicon glyphicon-envelope"></i>
                    <input name="email" type="email" class="form-control" id="inputEmail3" value="{{ old('email') }}" aria-describedby="basic-addon3" placeholder="Email address">
                </div>
                <div>&nbsp;</div>
                <div>
                    <button type="submit" class="btn btn-info form-control">
                        Reset Password
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</body>
@endsection
