<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 22px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 20px;
        }
        .message {
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 25px;
            font-size: 15px;
        }
        .message p {
            margin: 0 0 15px 0;
        }
        .info-box {
            background: #edf2f7;
            border-left: 4px solid #4299e1;
            padding: 18px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .info-box.success {
            background: #f0fdf4;
            border-left-color: #10b981;
        }
        .info-box.warning {
            background: #fffbeb;
            border-left-color: #f59e0b;
        }
        .info-box p {
            margin: 0;
            color: #2d3748;
            font-size: 14px;
            line-height: 1.6;
        }
        .status-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin: 20px 0;
        }
        .user-info {
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .user-info p {
            margin: 8px 0;
            color: #4a5568;
            font-size: 14px;
        }
        .user-info strong {
            color: #2d3748;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 30px 0;
        }
        .timeline {
            margin: 25px 0;
        }
        .timeline-item {
            display: flex;
            margin-bottom: 20px;
            position: relative;
        }
        .timeline-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #10b981;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .timeline-icon.pending {
            background: #f59e0b;
        }
        .timeline-icon.future {
            background: #cbd5e0;
        }
        .timeline-content {
            flex: 1;
            padding-top: 4px;
        }
        .timeline-content h4 {
            margin: 0 0 5px 0;
            color: #2d3748;
            font-size: 15px;
        }
        .timeline-content p {
            margin: 0;
            color: #718096;
            font-size: 13px;
        }
        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            color: #718096;
            font-size: 13px;
        }
        .footer p {
            margin: 8px 0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
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
            <div class="greeting">Welcome, {{ $profile->first_name }}! 🎉</div>

            <div class="info-box success">
                <p><strong>✅ Email Verified Successfully!</strong></p>
                <p>Your email address has been confirmed and your account has been created.</p>
            </div>

            <div class="message">
                <p>Thank you for registering with <strong>{{ config('app.name') }}</strong>. We're excited to have you join our investment platform!</p>
            </div>

            <div class="status-badge">
                ⏳ Account Status: Pending Admin Approval
            </div>

            <div class="user-info">
                <p><strong>Registration Details:</strong></p>
                <p><strong>Name:</strong> {{ $profile->first_name }} {{ $profile->last_name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Firm:</strong> {{ $profile->firm_name }}</p>
                <p><strong>Registered:</strong> {{ $user->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>

            <div class="divider"></div>

            <div class="message">
                <p><strong>What Happens Next?</strong></p>
            </div>

            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-icon">✓</div>
                    <div class="timeline-content">
                        <h4>Email Verified</h4>
                        <p>You successfully verified your email address</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon pending">⏳</div>
                    <div class="timeline-content">
                        <h4>Admin Review in Progress</h4>
                        <p>Our compliance team is reviewing your application</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon future">3</div>
                    <div class="timeline-content">
                        <h4>Approval Notification</h4>
                        <p>You'll receive an email once your account is approved</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon future">4</div>
                    <div class="timeline-content">
                        <h4>Full Platform Access</h4>
                        <p>Login and explore all investment opportunities</p>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="info-box warning">
                <p><strong>⚠️ Important Notice:</strong></p>
                <p>You will not be able to login until your account is approved by our admin team. This typically takes 1-2 business days. You will receive an email notification once your account is activated.</p>
            </div>

            <div class="message">
                <p><strong>Review Timeline:</strong></p>
                <ul style="margin-left: 20px; color: #4a5568;">
                    <li style="margin-bottom: 8px;">Standard applications: 1-2 business days</li>
                    <li style="margin-bottom: 8px;">Complex applications: 3-5 business days</li>
                    <li style="margin-bottom: 8px;">You'll be notified via email of the decision</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') }}/privacy">Privacy Policy</a> |
                <a href="{{ config('app.url') }}/terms">Terms of Use</a> |
                <a href="{{ config('app.url') }}/contact">Contact Us</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #a0aec0;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
