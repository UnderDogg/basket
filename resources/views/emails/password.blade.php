<h1>Password Reset Requested</h1>
<p>You have recently made a request to reset your password. Please follow the link below to change your password</p>
<p>
    <a href="{{ url('password/reset/'.$token) }}">
        {{ url('password/reset/'.$token) }}
    </a>
</p>
<p>This email will remain valid for 1 hour.</p>
