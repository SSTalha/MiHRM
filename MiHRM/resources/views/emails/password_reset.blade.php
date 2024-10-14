<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>You requested a password reset. Click the link below to reset your password:</p>
    <a href="{{ $resetUrl }}">Reset Password</a>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>