@extends('emails.layouts.html_master')

@section('content')
    <p style="margin: 0 0 8px;">Dear {{ $user->profile->first_name ?? 'Investor' }},</p>

    <p style="font-size: 12px; margin: 0 0 15px; color: #172971; font-family: Arial, sans-serif;">Thank you for registering
        with Pinnacle Capital's Alt's Platform</p>

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

    <p style="margin: 0 0 12px;"><strong>What Happens Next:</strong></p>
    <p style="margin: 0 0 12px;"> Once you email is verified, you will receive provisional access to the platform. This
        allows you to: </p>
    <ol style="padding-left: 20px; margin: 0 0 16px; font-size: 15px;">
        <li style="margin-bottom: 8px;">Browse all available investment offerings.</li>
        <li style="margin-bottom: 8px;">View high-level deal summaries and performance highlights.</li>
    </ol>

    <p style="margin: 0 0 12px;"><strong>Please note the provisional access does not include:</strong></p>
    <ul style="padding-left: 20px; margin: 0 0 16px; font-size: 15px;">
        <li style="margin-bottom: 8px;">Downloading offerings documents.</li>
        <li style="margin-bottom: 8px;">Viewing confidential data rooms.</li>
        <li style="margin-bottom: 8px;">Submitting investment indications or completing subscription steps.</li>
    </ul>

    <p style="margin: 0 0 12px;"> Full access will be enabled after our compliance team has approves your account. typically
        within 24 hours.</p>

    <p style="margin: 0 0 12px;">Your will receive a confirmation email as soon as your account is approved.</p>

    <p style="margin: 0 0 12px;"><strong>Need Assistance?</strong></p>

    <p style="margin: 0 0 16px;">If you have any questions or need help navigating the platform, contact us at <a href="mailto:info@pinnaclecapgrp.com"
            style="color: #172971; text-decoration: none;">info@pinnaclecapgrp.com</a></p>

    <p style="margin: 0 0 12px;">If you did not register for an account, please disregard this message.</p>

    <p style="margin: 0;">Best regards,<br>
        <strong style="font-weight: bold;">The Pinnacle Alt's Platform Team</strong><br>
        <strong style="font-weight: bold;">Pinnacle Capital Group</strong>
    </p>
@endsection
