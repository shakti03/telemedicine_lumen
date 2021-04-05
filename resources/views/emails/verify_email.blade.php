<html>
    <body>
        <p>Hello {{ $user->full_name }},</p>
        <br/>
        <p>You are registerd successfully. Please click the below link to verify your account</p>
        <a href="{{ URL::to('/verify-email?token='.$user->verify_token) }}">Verify User</a>
    </body>
</html>