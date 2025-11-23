<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Firm;
use App\Models\User;
use App\Models\Profiles;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AccessLevelService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\RegisterRequest;
use App\Mail\AdminNewRegistrationMail;
use Illuminate\Auth\Events\Registered;
use App\Mail\WelcomePendingApprovalMail;
use App\Models\ComplianceAcknowledgment;
use App\Mail\UserWelcomeVerificationMail;

class AuthenticationController extends Controller
{
    public function __construct(
        private AccessLevelService $accessLevelService
    ) {}

    public function register(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {

            // Generate secure token
            $token = $this->accessLevelService->generateSecureToken();

            // 1. Create User (unverified)
            $user = User::create([
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'access_level'  => 'provisional', // default pending approval
                'is_active'     => false,
                'role'          => 'user',
                'email_verification_token' => $token,
            ]);

            // 2. Create Profile
            Profiles::create([
                'user_id'            => $user->id,
                'first_name'         => $request->first_name,
                'last_name'          => $request->last_name,
                'firm_name'          => $request->firm_name,
                'phone'              => $request->phone,
                'country'            => $request->country,
                'investor_type'      => $request->investor_type,
                'investor_type_other' => $request->investor_type === 'other' ? $request->investor_type_other : null,
            ]);

            // 3. Create Firm
            Firm::create([
                'profile_id'                    => $user->profile->id,
                'is_registered'                 => $request->boolean('is_registered'),
                'firm_crd'                      => $request->firm_crd,
                'individual_crd'                => $request->individual_crd,
                'firm_aum'                      => $request->firm_aum,
                'address'                       => $request->address,
                'city'                          => $request->city,
                'state'                         => $request->state,
                'zip'                           => $request->zip,
                'explanation_if_not_registered' => $request->explanation_if_not_registered,
            ]);

            // 4. Create Compliance Acknowledgment
            ComplianceAcknowledgment::create([
                'user_id'                   => $user->id,
                'terms_agreed'              => true,
                'terms_agreed_at'           => now(),
                'privacy_agreed'            => true,
                'privacy_agreed_at'         => now(),
                'investor_acknowledgment'   => true,
                'investor_acknowledgment_at' => now(),
                'confidentiality_agreed'    => true,
                'confidentiality_agreed_at' => now(),
                'marketing_opt_in'          => $request->boolean('marketing_opt_in'),
                'marketing_opt_in_at'       => $request->boolean('marketing_opt_in') ? now() : null,
            ]);

            // 5. Fire Registered Event → Laravel automatically sends verification email
            event(new Registered($user));

            $verifyUrl = route('verify.email', ['token' => $token]);

            // 6. Send Custom Welcome + Verification Email (Optional, better design)
            Mail::to($user->email)->send(new UserWelcomeVerificationMail($user, $verifyUrl));

            // 7. Notify Admin(s)
            $this->notifyAdminsAboutNewRegistration($user);

            return response()->json([
                'message' => 'Registration successful! Please check your email to verify your account.',
                'user'    => [
                    'id'    => $user->id,
                    'name'  => $user->profile->first_name . ' ' . $user->profile->last_name,
                    'email' => $user->email,
                ]
            ], 201);
        });
    }


    // Admin Notification
    private function notifyAdminsAboutNewRegistration(User $user)
    {
        $adminEmails = User::where('role', 'admin')
            ->whereNotNull('email_verified_at')
            ->pluck('email')
            ->merge(['arifulislam6460@gmail.com']) // fallback + primary
            ->unique()
            ->filter();

        foreach ($adminEmails as $email) {
            // dd($email);
            Mail::to($email)->send(new AdminNewRegistrationMail($user));
        }
    }

    /**
     * Verify email from the link sent to user
     */
    public function verifyEmail(string $token): RedirectResponse
    {
        // fine user
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->away(
                "https://pinnaclealts.com/?error=invalid&message=" . urlencode("Invalid or already used verification link.")
            );
        }

        // check already verified
        if ($user->hasVerifiedEmail()) {
            $user->email_verification_token = null;
            $user->save();

            return redirect()->away("https://pinnaclealts.com/?verified=success&status=already_verified");
        }

        // verify user
        $user->markEmailAsVerified();
        $user->email_verification_token = null; // one-time use
        $user->save();

        // welcome mail
        Mail::to($user->email)->send(new WelcomePendingApprovalMail($user));

        // success redirect
        return redirect()->away(
            "https://pinnaclealts.com/?verified=success&status=pending_approval"
        );
    }
}
