@extends('guest',  ['action' => '/password/email'])

@section('content')
    <h5 class="soft-color">
        <span aria-hidden="true">Forgot your password?</span>
    </h5>
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
@endsection
