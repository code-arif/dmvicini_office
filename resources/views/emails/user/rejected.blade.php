@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.frontend_url')])
            <h1 style="color: #172971; font-weight: bold;">Pinnacle Alts</h1>
        @endcomponent
    @endslot

    {{-- Body --}}
    <div style="background-color: #ffffff; padding: 30px; font-family: Arial, sans-serif; color: #000000;">
        <h2 style="color: #172971;">Registration Update</h2>

        <p>Dear {{ $user->profile->first_name }},</p>

        <p>We regret to inform you that your registration request was <strong style="color: #d32f2f;">not approved</strong> at this time.</p>

        <p>Possible reasons:</p>
        <ul style="color: #000000; margin: 15px 0; padding-left: 20px;">
            <li>Missing or invalid CRD information</li>
            <li>Incomplete investor accreditation details</li>
            <li>Compliance policy requirements not met</li>
        </ul>

        <p>You may re-submit your application with updated information.</p>

        @component('mail::button', [
            'url' => $retryUrl,
            'color' => 'primary'
        ])
        Re-Apply Now
        @endcomponent

        <p style="margin-top: 25px; color: #666;">
            If you believe this was a mistake, please contact <a href="mailto:compliance@pinnaclealts.com" style="color: #172971;">compliance@pinnaclealts.com</a>.
        </p>
    </div>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <p style="color: #666; font-size: 12px;">
                © {{ date('Y') }} Pinnacle Alts. All rights reserved.<br>
                This is an automated message. Please do not reply.
            </p>
        @endcomponent
    @endslot
@endcomponent
