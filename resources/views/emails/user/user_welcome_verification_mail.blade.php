@extends('emails.layouts.html_master')

@section('content')
    <h2 style="font-size: 22px; margin: 0 0 20px; color: #172971; font-family: Arial, sans-serif;">Thank you for registering
        with Pinnacle Capital's Alt's Platform</h2>

    <p style="margin: 0 0 16px;">Dear {{ $user->profile->first_name ?? 'Investor' }},</p>

    <p style="margin: 0 0 16px;">We’re pleased to welcome you to our private investment marketplace.</p>

    <p style="margin: 0 0 24px;">To complete your registration, please verify your email address:</p>

    <!-- Centered Button -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 25px 0;">
        <tr>
            <td align="center" style="text-align: center;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" bgcolor="#172971" style="border-radius: 6px;">
                            <a href="{{ $verifyUrl }}" target="_blank"
                                style="display: inline-block; padding: 14px 36px; font-weight: 600; font-size: 16px; color: #ffffff; text-decoration: none; font-family: Arial, sans-serif;">
                                Verify Email Address
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background: #f7fafc; border-left: 4px solid #172971; padding: 20px; margin: 30px 0; border-radius: 0 4px 4px 0; font-size: 14px; color: #4a5568;">
        <tr>
            <td>
                <strong style="font-weight: bold;">Important Notice:</strong><br><br>
                WhiWhile our admin is reviewing your application, you will have provisional access to review the platform.
                Once you have been approved, typically within 1-2 business days, you will receive an email notification and
                have full access. Please contact us at <a href="mailto:info@pinnaclecapgrp.com"
                    style="color: #172971; text-decoration: none;">info@pinnaclecapgrp.com</a>
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 12px;"><strong>What Happens Next:</strong></p>
    <ol style="padding-left: 20px; margin: 0 0 16px; font-size: 15px;">
        <li style="margin-bottom: 8px;">You will receive a separate email once your account is fully approved.</li>
        <li style="margin-bottom: 8px;">Our compliance team is reviewing your application – feel free to explore the
            platform while awaiting approval.</li>
        <li style="margin-bottom: 8px;">After approval, you’ll gain full access to browse and request investments.</li>
    </ol>

    <p style="margin: 0 0 16px;">If you did not register for an account, please disregard this email.</p>

    <p style="margin: 0;">Best regards,<br><strong style="font-weight: bold;">The Pinnacle Alt's Platform Team</strong></p>
@endsection
