@extends('emails.layouts.html_master')

@section('content')
    <h2 style="font-size: 22px; margin: 0 0 20px; color: #172971; font-family: Arial, sans-serif;">
        New User Registration – Awaiting Review
    </h2>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        A new investor has registered and is pending compliance approval.
    </p>

    <!-- Notice Box (Styled with Tables for Consistency) -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background: #f7fafc; border-left: 4px solid #172971; padding: 20px; margin: 25px 0; border-radius: 0 4px 4px 0; font-family: Arial, sans-serif; font-size: 14px; color: #4a5568;">
        <tr>
            <td>
                <strong style="font-weight: bold;">User Details</strong><br><br>
                <strong>Name:</strong> {{ $user->profile->first_name }} {{ $user->profile->last_name }}<br>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Firm:</strong> {{ $user->profile->firm_name ?? 'Not provided' }}<br>
                <strong>Phone:</strong> {{ $user->profile->phone ?? 'Not provided' }}<br>
                <strong>Country:</strong> {{ $user->profile->country ?? 'Not provided' }}<br>
                <strong>Investor Type:</strong> {{ ucfirst(str_replace('_', ' ', $user->profile->investor_type)) }}
                @if ($user->profile->investor_type === 'other')
                    → {{ $user->profile->investor_type_other }}
                @endif
                <br><br>
                <strong>Registered At:</strong> {{ $user->created_at->format('F j, Y \a\t g:i A') }} (UTC)
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 10px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        <strong>Action Required:</strong>
    </p>
    <p style="margin: 0 0 20px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Please review the user’s profile and firm details in the admin panel:
    </p>

    <!-- Centered Admin Button -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 25px 0;">
        <tr>
            <td align="center" style="text-align: center;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" bgcolor="#172971" style="border-radius: 6px;">
                            <a href="{{ route('admin.users.show', $user->id) }}" target="_blank"
                                style="display: inline-block; padding: 14px 36px; font-weight: 600; font-size: 16px; color: #ffffff; text-decoration: none; font-family: Arial, sans-serif;">
                                Review User Profile
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 16px; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        The user currently has provisional access to browse the platform.
    </p>

    <p style="margin: 0; font-family: Arial, sans-serif; font-size: 15px; color: #4a5568;">
        Thank you,<br>
        <strong style="font-weight: bold;">Pinnacle Alt's Platform System</strong>
    </p>
@endsection
