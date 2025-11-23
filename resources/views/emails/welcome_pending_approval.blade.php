@extends('emails.layouts.html_master')

@section('content')
    <h2 style="font-size: 22px; margin: 0 0 20px; color: #172971; font-family: Arial, sans-serif;">
        Email Verified – Awaiting Final Approval
    </h2>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Dear {{ $user->profile->first_name ?? 'Investor' }},
    </p>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Thank you for verifying your email address. Your account is now provisionally active.
    </p>

    <p style="margin: 0 0 20px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Our compliance team is reviewing your profile. Full platform access will be granted shortly —
        typically within <strong>1–2 business days</strong>.
    </p>

    <!-- Notice Box -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background: #f7fafc; border-left: 4px solid #172971; padding: 20px; margin: 25px 0; border-radius: 0 4px 4px 0; font-family: Arial, sans-serif; font-size: 14px; color: #4a5568;">
        <tr>
            <td>
                <strong style="font-weight: bold;">What to Expect:</strong><br><br>
                • You can already <strong>explore the platform</strong> with limited access.<br>
                • Once approved, you’ll receive a confirmation email and get<strong>full privileges</strong>.<br>
                • Questions? Contact us at <a href="mailto:info@pinnaclecapgrp.com"
                    style="color: #172971; text-decoration: none;">info@pinnaclecapgrp.com</a>
            </td>
        </tr>
    </table>

    <p style="margin: 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Thank you for your patience.<br>
        <strong style="font-weight: bold;">The Pinnacle Alt's Platform Team</strong>
    </p>
@endsection
