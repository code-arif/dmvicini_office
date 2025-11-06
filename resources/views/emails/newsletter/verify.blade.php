<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Verify Subscription</title>
</head>

<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px;">
    <div
        style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #1a1a1a;">Welcome to Pinnacle Capital Group!</h2>
        <p>You're one step away from receiving exclusive investment tips.</p>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p style="margin: 30px 0;">
            <a href="{{ $verifyUrl }}"
                style="background: #1a1a1a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Verify Subscription
            </a>
        </p>
        <p style="font-size: 12px; color: #666;">
            If you didn't subscribe, please ignore this email.
        </p>
    </div>
</body>

</html>
