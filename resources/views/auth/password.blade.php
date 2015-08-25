<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Basket</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/icon" href="{!! asset('/image/xfavicon-64.ico.pagespeed.ic.w5mJPa9jXS.png') !!}" sizes="64x64">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
        .inner-addon {
            position: relative;
        }

        .inner-addon .glyphicon {
            position: absolute;
            padding: 10px;
            pointer-events: none;
            color: #bbb;
        }

        /* align icon */
        .left-addon .glyphicon  { left:  2.5px;}
        .right-addon a { display: inline-block; position: absolute; right: 5px; padding: 10px;font-size: 11px;}

        /* add padding  */
        .left-addon input  { padding-left:  35px; }
        .center-logo {
            text-align: center;
        }
        .center-box {
            height: 260px;
            width: 100vw;
            margin-top: 25vh;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <script src="/js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
<body style="height: 100vh;">
<div class="form-group panel-heading center-box">
    <form method="POST" action="/password/email">
        {!! csrf_field() !!}

        <div class="row ">
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
                {{--<div class="input-group">--}}
                    {{--<input class="form-control" type="email" name="email" value="{{ old('email') }}" aria-describedby="basic-addon3">--}}
                        {{--<span class="input-group-addon" id="basic-addon3">--}}
                            {{--<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>--}}
                        {{--</span>--}}
                {{--</div>--}}
                {{-- END EMAIL ADDRESS FIELD --}}
                <div>&nbsp;</div>
                <div>
                    <button type="submit" class="btn btn-info form-control">
                        Reset Password
                    </button>
                </div>
                <div>&nbsp;</div><div>&nbsp;</div>
            </div>
        </div>
    </form>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/js/main.js"></script>
</body>
</html>
