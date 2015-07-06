@extends('master')

@section('content')
<div>&nbsp;</div>
<div>&nbsp;</div>
<div class="form-group panel-heading">
    <form method="POST">

        {!! csrf_field() !!}

        <div class="row ">
            <div class="col-md-8 col-md-offset-2 jumbotron">

                {{-- START OUTPUT RESPONSE --}}
                @if($errors->any()) <div class="alert alert-danger" data-dismiss="alert">
                    <button type="button" class="close">&times;</button>
                    {{$errors->first()}}
                    Please ensure that the information you have provided is complete and without mistakes.
                </div> @endif
                {{-- END OUTPUT RESPONSE --}}

                <h2 style="color: #666666">Login Panel</h2>

                <div>&nbsp;</div>
                <h4 class="fieldLabel">
                    Email Address
                </h4>
                <div class="input-group">
                    <input name="email" type="email" class="form-control" id="inputEmail3" value="{{ old('email') }}" aria-describedby="basic-addon3">
                        <span class="input-group-addon" id="basic-addon3">
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                        </span>
                </div>
                <h4 class="fieldLabel">
                    Password
                </h4>
                <div class="input-group">
                    <input class="form-control" type="password" name="password" aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2">
                            <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                        </span>
                </div>
                <div>&nbsp;</div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <input type="checkbox" aria-label="...">
                    </span>
                    <label class="form-control">Remember Me</label>
                    <span class="input-group-btn">
                        <a href="/password/email">
                            <button class="btn form-control input-group-addon" type="button">Forgotten Your Password?</button>
                        </a>
                    </span>
                </div>

                <div>&nbsp;</div>
                <div>
                    <button type="submit" class="btn btn-info btn-md pull-right">
                        Login To Basket
                    </button>
                </div>
                <div>&nbsp;</div><div>&nbsp;</div>


        {{--<fieldset>--}}
            {{--<legend>Login</legend>--}}

            {{--<div class="form-group">--}}
                {{--<label for="inputEmail3" class="col-sm-2 control-label">Email</label>--}}
                {{--<div class="col-sm-10">--}}
                    {{--<input name="email" type="email" class="form-control" id="inputEmail3" placeholder="Email" value="{{ old('email') }}">--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<label for="inputPassword3" class="col-sm-2 control-label">Password</label>--}}
                {{--<div class="col-sm-10">--}}
                    {{--<input name="password" type="password" class="form-control" id="inputPassword3" placeholder="Password">--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<div class="col-sm-offset-2 col-sm-10">--}}
                    {{--<div class="checkbox">--}}
                        {{--<label>--}}
                            {{--<input name="remember" type="checkbox"> Remember me--}}
                        {{--</label>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<div class="col-sm-offset-2 col-sm-10">--}}

                        {{--<a href="/password/email">Forgotten Your Password?</a>--}}

                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--<div class="col-sm-offset-2 col-sm-10">--}}
                    {{--<button type="submit" class="btn btn-default">Login</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</fieldset>--}}
    </form>
</div>
@endsection
