@extends('emails.layouts.html_master')

@section('content')
    <h2 style="font-size: 22px; margin: 0 0 20px; color: #172971; font-family: Arial, sans-serif;">
        Thank You for Your Interest!
    </h2>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Hi {{ $user->profile->first_name ?? $user->first_name ?? 'Investor' }},
    </p>

    <p style="margin: 0 0 20px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        We have successfully recorded your interest in the following deal:
    </p>

    <!-- Deal Highlight -->
    <div style="background: #ebf4ff; padding: 20px; border-left: 5px solid #172971; margin: 25px 0; border-radius: 4px; font-family: Arial, sans-serif;">
        <p style="margin: 0; font-size: 18px; font-weight: 600; color: #172971;">
            {{ $investment->title }}
        </p>
        @if($investment->short_description)
            <p style="margin: 10px 0 0; font-size: 14px; color: #4a5568;">
                {{ Str::limit($investment->short_description, 180) }}
            </p>
        @endif
    </div>

    <p style="margin: 20px 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        A member of our team will review your interest and reach out to you shortly with next steps or additional information.
    </p>

    <p style="margin: 20px 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        You can always view your interested deals in your dashboard.
    </p>

    <!-- Button -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" bgcolor="#172971" style="border-radius: 6px;">
                            <a href="{{ url('/dashboard/interested-deals') }}"
                               target="_blank"
                               style="display: inline-block; padding: 14px 36px; font-weight: 600; font-size: 16px; color: #ffffff; text-decoration: none; font-family: Arial, sans-serif;">
                                View My Interested Deals
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin: 20px 0 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Thank you for using Alt's Platform.<br>
        We’re excited to help you explore this opportunity!
    </p>

    <p style="margin: 30px 0 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Best regards,<br>
        <strong style="font-weight: bold;">The Alt's Platform Team</strong>
    </p>
@endsection
