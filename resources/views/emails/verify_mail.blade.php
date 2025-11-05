<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
            line-height: 1.6;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: #172971;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 20px;
        }

        .message {
            color: #4a5568;
            font-size: 15px;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .cta-container {
            text-align: center;
            margin: 40px 0;
        }

        .cta-button {
            display: inline-block;
            background: #172971;
            color: #ffffff;
            text-decoration: none;
            padding: 16px 48px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
        }

        .info-box {
            background: #f7fafc;
            border-left: 4px solid #172971;
            padding: 16px 20px;
            margin: 30px 0;
            border-radius: 4px;
        }

        .info-box p {
            color: #4a5568;
            font-size: 14px;
            margin: 0;
        }

        .info-box strong {
            color: #2d3748;
        }

        .warning {
            background: #fff5f5;
            border-left-color: #fc8181;
            margin-top: 20px;
        }

        .warning p {
            color: #742a2a;
        }

        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            color: #718096;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .footer a {
            color: #172971;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 30px 0;
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 30px 20px;
            }

            .cta-button {
                padding: 14px 36px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Investment Platform</p>
        </div>

        <div class="content">
            <div class="greeting">Welcome!</div>

            <div class="message">
                <p>Thank you for registering with <strong>{{ config('app.name') }}</strong>.</p>
                <p>To complete your registration and access our investment platform, please verify your email address by
                    clicking the button below:</p>
            </div>

            <div class="cta-container">
                <!-- Direct link to verification page with token -->
                <a href="{{ $verifyUrl }}" class="cta-button">Verify Email Address</a>
            </div>

            <div class="info-box">
                <p><strong>⏱️ Important:</strong> This verification link will expire in <strong>{{ $expiresIn }}
                        minutes</strong>. Please complete verification promptly.</p>
            </div>

            <div class="divider"></div>

            <div class="message">
                <p><strong>What happens next?</strong></p>
                <ul style="margin-left: 20px; color: #4a5568;">
                    <li style="margin-bottom: 8px;">Your account will be created upon verification</li>
                    <li style="margin-bottom: 8px;">You'll receive provisional access for 7 days</li>
                    <li style="margin-bottom: 8px;">Our compliance team will review your application</li>
                    <li style="margin-bottom: 8px;">You'll be notified once full access is granted</li>
                </ul>
            </div>

            <div class="info-box warning">
                <p><strong>⚠️ Didn't create an account?</strong> If you didn't request this registration, please ignore
                    this email. No account will be created without verification.</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') }}/privacy">Privacy Policy</a> |
                <a href="{{ config('app.url') }}/terms">Terms of Use</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #a0aec0;">
                This email was sent to verify your registration. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>

</html>
