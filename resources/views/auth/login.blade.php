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
        <form method="POST">

            {!! csrf_field() !!}

            <div class="row ">
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
                    {{--<div>&nbsp;</div>--}}
                    {{--<div class="input-group">--}}
                        {{--<span class="input-group-addon">--}}
                            {{--<input type="checkbox" aria-label="...">--}}
                        {{--</span>--}}
                        {{--<label class="form-control">Remember Me</label>--}}
                        {{--<span class="input-group-btn">--}}
                            {{--<a href="/password/email">--}}
                                {{--<button class="btn form-control input-group-addon" type="button">Forgot Your Password?</button>--}}
                            {{--</a>--}}
                        {{--</span>--}}
                    {{--</div>--}}

                    {{--<div>&nbsp;</div>--}}
                    <div>
                        <button type="submit" class="btn btn-info form-control">
                            Sign In
                        </button>
                    </div>
                    <div>&nbsp;</div><div>&nbsp;</div>
        </form>
</div>
</body>
</html>
