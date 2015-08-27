@extends('master')

@section('page')
@if(Auth::check())
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                {!! HTML::image('image/ain-logo-dark-large.png', 'afforditNOW', array('style' => 'height: 28px;')) !!}
            </a>
        </div>
        {{--NAVIGATION BAR--}}
        <div id="navbar" class="navbar-collapse collapse">
            @if ( \Auth::check())
                <ul class="nav navbar-nav">
                    @if(Auth::user()->can('applications-view'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Applications <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                @foreach($available_installations as $id => $installation)
                                    <li class="dropdown-header">{{ $installation->name }}</li>
                                    <li><a href="/installations/{{ $installation->id }}/applications">Applications List</a></li>
                                    <li><a href="/installations/{{ $installation->id }}/applications/pending-cancellations">Pending Cancellation List</a></li>
                                    @if(count($available_installations) != ($id+1))
                                            <li role="separator" class="divider"></li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->can('reports-view'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/merchants/{{ Auth::user()->merchant_id?Auth::user()->merchant_id:1 }}/settlements">Settlements</a></li>
                                <li><a href="/merchants/{{ Auth::user()->merchant_id?Auth::user()->merchant_id:1 }}/partial-refunds">Partial Refunds</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::user()->can('merchants-view') || Auth::user()->can('locations-view') ||Auth::user()->can('users-view') || Auth::user()->can('roles-view'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->can('merchants-view') || Auth::user()->can('locations-view'))
                                    <li class="dropdown-header">Manager</li>
                                    @if(Auth::user()->can('merchants-view'))
                                        <li><a href="/installations">Installations</a></li>
                                    @endif
                                    @if(Auth::user()->can('locations-view'))
                                        <li><a href="/locations">Locations</a></li>
                                    @endif
                                    @if(Auth::user()->can('users-view') || Auth::user()->can('roles-view'))
                                        <li role="separator" class="divider"></li>
                                    @endif
                                @endif
                                @if(Auth::user()->can('users-view') || Auth::user()->can('merchants-view') || Auth::user()->can('roles-view'))
                                    <li class="dropdown-header">Administrator</li>
                                    @if(Auth::user()->can('users-view'))
                                        <li><a href="/users">Users</a></li>
                                    @endif
                                    @if(Auth::user()->can('merchants-view'))
                                        <li><a href="/merchants">Merchants</a></li>
                                    @endif
                                    @if(Auth::user()->can('roles-view'))
                                        <li><a href="/roles">Roles & Permissions</a></li>
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{!! Auth::user()->name !!}
                            {!! HTML::image('//www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email))) . '?size=20', Auth::user()->name) !!}
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{URL::to('/account')}}">Account</a></li>
                            <li><a href="{{URL::to('/logout')}}">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <br>
            @if($errors->any())
                <div id="actionMessage" hidden="hidden">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                </div>
            @endif
            {{--CUSTOM ASSIGNED ERRORS--}}
            @foreach($messages as $k => $v)
                <div id="actionMessage" hidden="hidden">
                    <div class="alert alert-{{ ($k == 'error')?'danger':$k }} alert-dismissible" role="alert">
                        <button type="button" class="close message_close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p>{{ $v }}</p>
                    </div>
                </div>
            @endforeach
            @yield('content')
        </div>
    </div>

    <hr>

    <footer class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-right">
                {{--SHOW VERSION IF AVAILABLE--}}
                @include('includes.page.version')
            </div>
        </div>
    </footer>
</div> <!-- /container -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.writex('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/js/main.js"></script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
    function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
e=o.createElement(i);r=o.getElementsByTagName(i)[0];
e.src='//www.google-analytics.com/analytics.js';
r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
ga('create','UA-XXXXX-X','auto');ga('send','pageview');
</script>
@yield('scripts')
</body>
@endif
@endsection