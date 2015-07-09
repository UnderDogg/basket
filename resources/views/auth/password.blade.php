@extends('master')

@section('content')

    <div>&nbsp;</div>
    <div class="form-group panel-heading">
        <form method="POST" action="/password/email">
            {!! csrf_field() !!}

            <div class="row ">
                <div class="col-md-8 col-md-offset-2 jumbotron">

                    {{-- START OUTPUT RESPONSE --}}
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

                    <h2 style="color: #666666">Request To Reset Password</h2>

                    {{-- START EMAIL ADDRESS FIELD --}}
                    <div>&nbsp;</div>
                    <div class="alert alert-info more_info_box" role="alert" style="display: none">
                        <button type="button" class="close more_info_close">&times;</button>
                        <h4>Email Address</h4>
                        Please provide the email address that is used for your account.  We will then send you an email
                        to that address containing further instructions for, safely and securely, resetting your
                        password.
                    </div>
                    <h4 class="fieldLabel">
                        Email Address
                        <span class="more_info_question" aria-hidden="true">(?)</span>
                    </h4>
                    <div class="input-group">
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" aria-describedby="basic-addon3">
                        <span class="input-group-addon" id="basic-addon3">
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                        </span>
                    </div>
                    {{-- END EMAIL ADDRESS FIELD --}}
                    <div>&nbsp;</div>
                    <div>
                        <button type="submit" class="btn btn-info btn-md pull-right">
                            Send Email
                        </button>
                    </div>
                    <div>&nbsp;</div><div>&nbsp;</div>
                </div>
            </div>
        </form>
    </div>
@endsection
