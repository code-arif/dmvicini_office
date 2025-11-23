@extends('emails.layouts.html_master')

@section('content')
    <h2 style="font-size: 22px; margin: 0 0 20px; color: #172971; font-family: Arial, sans-serif;">
        One-Time Password (OTP)
    </h2>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Dear {{ $user->profile->first_name ?? 'Valued Investor' }},
    </p>

    <p style="margin: 0 0 20px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Use the code below to complete your request. This OTP is valid for <strong>5 minutes</strong>.
    </p>

    <!-- OTP Code Box -->
    <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center"
                style="background: #f7fafc; padding: 16px 24px; border-radius: 6px; font-size: 22px; font-weight: bold; letter-spacing: 3px; color: #172971; font-family: Arial, sans-serif; margin: 20px 0; display: inline-block;">
                {{ $otp }}
            </td>
        </tr>
    </table>

    <p style="margin: 20px 0 0; font-family: Arial, sans-serif; font-size: 14px; color: #a0aec0;">
        Do not share this code with anyone.
    </p>

    <p style="margin: 20px 0 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Best regards,<br>
        <strong style="font-weight: bold;">The Pinnacle Alt's Platform Team</strong>
    </p>
@endsection
