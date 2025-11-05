@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.frontend_url')])
            <h1 style="color: #172971; font-weight: bold;">Pinnacle Alts</h1>
        @endcomponent
    @endslot

    {{-- Body --}}
    <div style="background-color: #ffffff; padding: 30px; font-family: Arial, sans-serif; color: #000000;">
        <h2 style="color: #172971;">Congratulations, {{ $user->profile->first_name }}!</h2>

        <p>Your registration has been <strong style="color: #172971;">approved</strong> by our compliance team.</p>

        <p>You now have <strong>full access</strong> to the Pinnacle Alts investment platform.</p>

        @component('mail::button', [
            'url' => $loginUrl,
            'color' => 'primary'
        ])
        Log In to Your Account
        @endcomponent

        <p style="margin-top: 25px; color: #666;">
            If you have any questions, feel free to contact our support team.
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
