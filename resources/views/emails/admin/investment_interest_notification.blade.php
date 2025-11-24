@extends('emails.layouts.html_master')

@section('content')
    <h2 style="font-size: 22px; margin: 0 0 20px; color: #172971; font-family: Arial, sans-serif;">
        New Interest Received – {{ $investment->title }}
    </h2>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        An investor has expressed interest in one of the deals on Alt's Platform.
    </p>

    <!-- Deal Name Highlight -->
    <p
        style="margin: 16px 0; padding: 14px 18px; background: #ebf4ff; border-left: 4px solid #172971; font-family: Arial, sans-serif; font-size: 16px; color: #172971; font-weight: 600;">
        Deal: <strong>{{ $investment->title }}</strong>
    </p>

    <!-- Investor Details Box -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background: #f7fafc; border-left: 4px solid #172971; padding: 20px; margin: 25px 0; border-radius: 0 4px 4px 0; font-family: Arial, sans-serif; font-size: 14px; color: #4a5568;">
        <tr>
            <td>
                <strong style="font-weight: bold; color: #172971;">Investor Details</strong><br><br>
                <strong>Full Name:</strong> {{ $user->profile->first_name }} {{ $user->profile->last_name }}<br>
                <strong>Contact Email:</strong> <a href="mailto:{{ $user->email }}"
                    style="color: #172971; text-decoration: underline;">{{ $user->email }}</a><br>
                @if ($user->profile->firm_name)
                    <strong>Firm:</strong> {{ $user->profile->firm_name }}<br>
                @endif
                @if ($user->profile->phone)
                    <strong>Phone:</strong> {{ $user->profile->phone }}<br>
                @endif
                @if ($user->profile->country)
                    <strong>Country:</strong> {{ $user->profile->country }}<br>
                @endif
                <strong>Investor Type:</strong> {{ ucfirst(str_replace('_', ' ', $user->profile->investor_type)) }}
                @if ($user->profile->investor_type === 'other' && $user->profile->investor_type_other)
                    → {{ $user->profile->investor_type_other }}
                @endif
                <br><br>
                <strong>Interest Recorded At:</strong> {{ now()->format('F j, Y \a\t g:i A') }} (UTC)
            </td>
        </tr>
    </table>

    <p style="margin: 20px 0 10px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        <strong>Next Steps:</strong>
    </p>
    <p style="margin: 0 0 20px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        You can reach out to the investor directly using the email above or view their full profile in the admin panel.
    </p>

    <!-- Centered Button -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" bgcolor="#172971" style="border-radius: 6px;">
                            <a href="#" target="_blank"
                                style="display: inline-block; padding: 14px 36px; font-weight: 600; font-size: 16px; color: #ffffff; text-decoration: none; font-family: Arial, sans-serif;">
                                View Investor Profile
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin: 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Thank you,<br>
        <strong style="font-weight: bold;">Alt's Platform System</strong>
    </p>
@endsection
