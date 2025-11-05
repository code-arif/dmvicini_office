{{-- resources/views/emails/admin/new-registration.blade.php --}}
@component('mail::message')
# New User Registration

A new user has completed registration and is awaiting approval.

@component('mail::button', ['url' => $approveUrl, 'color' => 'primary'])
Approve User
@endcomponent

@component('mail::button', ['url' => $rejectUrl, 'color' => 'error'])
Reject User
@endcomponent

<div style="margin-top: 20px; font-size: 12px; color: #666;">
    This is an automated message. User will not be able to log in until approved.
</div>
@endcomponent
