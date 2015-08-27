@extends('main')

@section('content')

    <div>&nbsp;</div>
    <div class="form-group panel-heading">
        <form method="POST" action="/password/reset">

            {!! csrf_field() !!}
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="row ">
                <div class="col-md-8 col-md-offset-2 jumbotron">

                    {{-- START OUTPUT RESPONSE --}}
                    @if($errors->any()) <div class="alert alert-danger" data-dismiss="alert">
                        <button type="button" class="close">&times;</button>
                        {{$errors->first()}}
                        Please ensure that the information you have provided is complete and without mistakes.
                    </div> @endif
                    {{-- END OUTPUT RESPONSE --}}

                    <h2 style="color: #666666">Reset Account Password</h2>

                    {{-- START EMAIL ADDRESS FIELD --}}
                    <div>&nbsp;</div>
                    <div class="alert alert-info more_info_box" role="alert" style="display: none">
                        <button type="button" class="close more_info_close">&times;</button>
                        <h4>Email Address</h4>
                        For security purposes, please provide the email address that is used for your account.
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

                    {{-- START PASSWORD FIELD --}}
                    <div>&nbsp;</div>
                    <div class="alert alert-info more_info_box" role="alert" style="display: none">
                        <button type="button" class="close more_info_close">&times;</button>
                        <h4>New Password</h4>
                        Please choose a new password that is both memorable and secure.<br>
                        <h5>A Little Help?</h5>
                        <ul>
                            <li>Use a mixture of upper and lowercase letters.</li>
                            <li>Use a mixture of letters and numbers.</li>
                            <li>Choose a password that is more than 8 characters long.</li>
                        </ul>
                    </div>
                    <h4 class="fieldLabel">
                        New Password
                        <span class="more_info_question" aria-hidden="true">(?)</span>
                    </h4>
                    <div class="input-group">
                        <input class="form-control" type="password" name="password" aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2">
                            <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                        </span>
                    </div>
                    {{-- END PASSWORD FIELD --}}

                    {{-- START CONFIRMATION PASSWORD FIELD --}}
                    <div>&nbsp;</div>
                    <div class="alert alert-info more_info_box" role="alert" style="display: none">
                        <button type="button" class="close more_info_close">&times;</button>
                        <h4>Re-Type Password</h4>
                        You must retype your new password. This ensures that there are no typos.
                    </div>
                    <h4 class="fieldLabel">
                        Re-Type Password
                        <span class="more_info_question" aria-hidden="true">(?)</span>
                    </h4>
                    <div class="input-group">
                        <input class="form-control" type="password" name="password_confirmation" aria-describedby="basic-addon1">
                        <span class="input-group-addon" id="basic-addon1">
                            <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                        </span>
                    </div>
                    {{-- END CONFIRMATION PASSWORD FIELD --}}
                    <div>&nbsp;</div>
                    <div>
                        <button type="submit" class="btn btn-info btn-md pull-right">
                            Reset Password
                        </button>
                    </div>
                    <div>&nbsp;</div><div>&nbsp;</div>
                </div>
            </div>
        </form>
    </div>
@endsection
