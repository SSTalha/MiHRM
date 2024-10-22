<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perk Request Status</title>
</head>
<body>
    Dear {{ $username }},
    <br><br>
    @if($status === 'approved')
        <p>Congratulations! Your perk request has been <strong>approved</strong>.</p>
    @else
        <p>Unfortunately, your perk request has been <strong>rejected</strong>.</p>
    @endif
    <br>
    <p>Thank you for your understanding.</p>
</body>
</html>
