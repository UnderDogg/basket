<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>afforditNOW Retailer Area</title>
    <meta name="application-name" content="Retailer"/>

    @include('icons')

    <title>Basket</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/icon" href="{!! asset('/image/favicon.png') !!}" sizes="64x64">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="/css/bootstrap.min.css">--}}
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>

    @if (getenv('APP_ENV') == 'test')
        <style>
            #testing-banner {
                top: 0;
                position:fixed;
                background-color:{{getenv('TEST_BANNER_COLOUR') ? getenv('TEST_BANNER_COLOUR') : 'red'}};
                height: {{getenv('TEST_BANNER_HEIGHT') ? getenv('TEST_BANNER_HEIGHT') : '24px'}};
                width: 100%;
                z-index:2;
                text-align:{{getenv('TEST_BANNER_ALIGNMENT') ? getenv('TEST_BANNER_ALIGNMENT') : 'center'}};
                color:{{getenv('TEST_BANNER_FONT_COLOUR') ? getenv('TEST_BANNER_FONT_COLOUR') :'white'}};
                font-size:{{getenv('TEST_BANNER_FONT_SIZE') ? getenv('TEST_BANNER_FONT_SIZE')  : '16px' }};
            }

            .navbar-fixed-top {
                top: {{getenv('TEST_BANNER_HEIGHT') ? getenv('TEST_BANNER_HEIGHT') : '24px'}};
            }
        </style>
    @endif

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="{!! asset('formvalidation/dist/css/formValidation.min.css') !!}">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <script src="/js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    @yield('stylesheets')
</head>
    @yield('page')
</html>
