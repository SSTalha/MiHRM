<!DOCTYPE html>
<html>
<head>
    <title>Set Up Your Password</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Welcome! Please click the link below to set up your password:</p>
    <a href="{{ $setupUrl }}">Set up your password</a>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
