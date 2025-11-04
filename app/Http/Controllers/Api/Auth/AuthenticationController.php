<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Firm;
use App\Models\User;
use App\Models\Profiles;
use Illuminate\Http\Request;
use App\Models\AccessRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\RegistrationAttempt;
use App\Http\Controllers\Controller;
use App\Mail\RegistrationVerifyMail;
use App\Services\AccessLevelService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\RegisterRequest;
use App\Mail\WelcomePendingApprovalMail;
use App\Models\ComplianceAcknowledgment;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticationController extends Controller
{
    public function __construct(
        private AccessLevelService $accessLevelService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $ip = $request->ip();

        // Rate Limiting
        $emailKey = 'registration:email:' . $email;
        if (RateLimiter::tooManyAttempts($emailKey, 3)) {
            $seconds = RateLimiter::availableIn($emailKey);
            return response()->json([
                'message' => "Too many registration attempts. Please try again in " . ceil($seconds / 60) . " minutes."
            ], 429);
        }

        $ipKey = 'registration:ip:' . $ip;
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            return response()->json([
                'message' => 'Too many registration attempts from this IP. Please try again later.'
            ], 429);
        }

        // Log attempt
        RegistrationAttempt::create([
            'email' => $email,
            'ip_address' => $ip,
            'attempted_at' => now(),
        ]);

        RateLimiter::hit($emailKey, 3600);
        RateLimiter::hit($ipKey, 3600);

        // Generate token
        $token = $this->accessLevelService->generateSecureToken();
        $cacheKey = "registration:{$token}";

        // Prepare payload
        $payload = array_merge($request->validated(), [
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'registered_at' => now()->toDateTimeString(),
        ]);

        // Store in cache
        Cache::put($cacheKey, $payload, now()->addMinutes(5));

        // Create access request
        AccessRequest::create([
            'request_token' => $token,
            'status' => 'pending',
        ]);

        // Send verification email
        $verifyUrl = route('verify.email', ['token' => $token]);
        Mail::to($email)->queue(new RegistrationVerifyMail($verifyUrl));

        return response()->json([
            'message' => 'Verification email sent. Link valid for 5 minutes.',
            'expires_at' => now()->addMinutes(5)->diffForHumans(),
        ], 200);
    }

    /**
     * Verify email - NOW RETURNS REDIRECT INSTEAD OF JSON
     */
    public function verifyEmail(string $token): RedirectResponse
    {
        $cacheKey = "registration:{$token}";
        $payload = Cache::get($cacheKey);

        if (!$payload) {
            // Check if already processed
            $accessReq = AccessRequest::where('request_token', $token)->first();
            if ($accessReq && $accessReq->user_id) {
                return redirect('https://pinnaclealts.com/?error=already_used')
                    ->with('error', 'This registration link has already been used.');
            }

            return redirect('https://pinnaclealts.com/?error=expired')
                ->with('error', 'Token expired or invalid. Please re-submit registration.');
        }

        DB::beginTransaction();
        try {
            // Create User
            $user = User::create([
                'email' => $payload['email'],
                'password' => Hash::make($payload['password']),
                'email_verified_at' => now(),
                'role' => 'user',
                'access_level' => 'review', // Default to review
                'is_active' => false, // Not active until admin approves
            ]);

            // Create Profile
            $profile = Profiles::create([
                'user_id' => $user->id,
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'title' => $payload['title'] ?? null,
                'firm_name' => $payload['firm_name'],
                'phone' => $payload['phone'],
                'country' => $payload['country'],
                'investor_type' => $payload['investor_type'],
                'investor_type_other' => $payload['investor_type'] === 'other'
                    ? $payload['investor_type_other']
                    : null,
            ]);

            // Create Firm
            $firm = Firm::create([
                'profile_id' => $profile->id,
                'is_registered' => (bool)$payload['is_registered'],
                'firm_crd' => $payload['firm_crd'] ?? null,
                'individual_crd' => $payload['individual_crd'] ?? null,
                'firm_aum_min' => $payload['firm_aum_min'] ?? null,
                'firm_aum_max' => $payload['firm_aum_max'] ?? null,
                'address' => $payload['address'] ?? null,
                'explanation_if_not_registered' => $payload['explain_not_registered'] ?? null,
            ]);

            // Store Compliance Acknowledgments
            $now = now();
            ComplianceAcknowledgment::create([
                'user_id' => $user->id,
                'terms_agreed' => true,
                'terms_agreed_at' => $now,
                'privacy_agreed' => true,
                'privacy_agreed_at' => $now,
                'investor_acknowledgment' => true,
                'investor_acknowledgment_at' => $now,
                'confidentiality_agreed' => true,
                'confidentiality_agreed_at' => $now,
                'marketing_opt_in' => $payload['marketing_opt_in'] ?? false,
                'marketing_opt_in_at' => ($payload['marketing_opt_in'] ?? false) ? $now : null,
                'ip_address' => $payload['ip_address'] ?? null,
                'user_agent' => $payload['user_agent'] ?? null,
            ]);

            // Update Access Request
            $accessReq = AccessRequest::where('request_token', $token)->first();
            if ($accessReq) {
                $accessReq->update([
                    'user_id' => $user->id,
                    'status' => 'review',
                ]);
            }

            DB::commit();

            // Clear cache
            Cache::forget($cacheKey);

            // Send welcome email with pending approval notice
            Mail::to($user->email)->queue(new WelcomePendingApprovalMail($user, $profile));

            // Redirect to success page
            return redirect('https://pinnaclealts.com/?verified=success&status=pending_approval')
                ->with('success', 'Registration verified! Please wait for admin approval.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return redirect('https://pinnaclealts.com/?error=failed')
                ->with('error', 'Registration failed. Please try again or contact support.');
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request): JsonResponse
    {
        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ], 200);
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request): JsonResponse
    {
        // Delete all tokens
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices successfully.'
        ], 200);
    }

    /**
     * Get current user
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('profile');

        return response()->json([
            'user' => $user
        ], 200);
    }
}
