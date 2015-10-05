@extends('guest')

@section('content')
    <div class="inner-addon left-addon">
        <i class="glyphicon glyphicon-user"></i>
        <input name="email" type="email" class="form-control" id="inputEmail3" value="{{ old('email') }}" aria-describedby="basic-addon3" placeholder="Email address" tabindex="1">
    </div>
    <br/>
    <div class="inner-addon left-addon right-addon">
        <a href="/password/email">Forgot?</a>
        <i class="glyphicon glyphicon-asterisk"></i>
        <input class="form-control" type="password" name="password" aria-describedby="basic-addon2" placeholder="Password" tabindex="2">
    </div>
    <br/>
    <div>
        <button type="submit" class="btn btn-info form-control" tabindex="3">
            Sign In
        </button>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#loginForm').formValidation({
                framework: 'bootstrap',
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email address is required'
                            },
                            emailAddress: {
                                message: 'The input is not a valid email address'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            },
                        }
                    },
                }
            });
        });
    </script>
@endsection
