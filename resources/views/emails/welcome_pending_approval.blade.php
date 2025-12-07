@extends('emails.layouts.html_master')

@section('content')
    <h2 style="font-size: 22px; margin: 0 0 20px; color: #172971; font-family: Arial, sans-serif;">
        Your Account Has Been Approved
    </h2>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Dear {{ $user->profile->first_name ?? 'Investor' }},
    </p>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        We’re pleased to let you know that your account has been successfully approved by our compliance team.
        You now have full access to the Pinnacle Alts Platform.
    </p>

    <!-- Features Section -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="margin: 25px 0; border-radius: 0 4px 4px 0; font-family: Arial, sans-serif; font-size: 14px; color: #4a5568; padding: 8px 8px 8px 8px;">
        <tr>
            <td>
                <strong style="font-weight: bold;">With your approved account, you may now:</strong><br><br>
                <ul style="padding-left: 20px; margin: 0 0 16px; font-size: 15px;">
                    <li style="margin-bottom: 8px;">View full offering details</li>
                    <li style="margin-bottom: 8px;">Download offering documents (PPMs, Operating Agreements, financials,
                        etc.)</li>
                    <li style="margin-bottom: 8px;">Access data rooms and due diligence materials</li>
                    <li style="margin-bottom: 8px;">Begin the investment process for offerings you qualify for</li>
                </ul>
            </td>
        </tr>
    </table>


    <!-- Button -->
    <p style="text-align: center; margin: 30px 0;">
        <a href="https://pinnaclealts.com"
            style="background: #172971; color: #ffffff; padding: 12px 20px; font-size: 15px;
                  text-decoration: none; border-radius: 4px; font-family: Arial, sans-serif;">
            Log In to Your Account
        </a>
    </p>

    <!-- Support -->
    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        <strong>Need Support?</strong><br>
        If you have any questions or need help navigating the platform, feel free to reach out:
        <br><br>
        📧 <a href="mailto:info@pinnaclecapgrp.com" style="color: #172971; text-decoration: none;">
            info@pinnaclecapgrp.com
        </a>
    </p>

    <p style="margin: 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        We’re glad to have you as part of Pinnacle’s investor community.<br><br>
        Best regards,<br>
        <strong style="font-weight: bold;">The Pinnacle Alts Platform Team</strong><br>
        Pinnacle Capital Group
    </p>
@endsection
