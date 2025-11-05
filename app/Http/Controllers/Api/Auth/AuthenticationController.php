<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Firm;
use App\Models\User;
use App\Models\Profiles;
use App\Models\AccessRequest;
use App\Mail\NewRegistrationMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\RegistrationAttempt;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\RegistrationVerifyMail;
use App\Services\AccessLevelService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\RegisterRequest;
use App\Mail\WelcomePendingApprovalMail;
use App\Models\ComplianceAcknowledgment;
use Illuminate\Support\Facades\Validator;
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
        Cache::put($cacheKey, $payload, now()->addMinutes(15));

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
     * Verify email – returns a redirect (frontend page)
     */
    public function verifyEmail(string $token): RedirectResponse
    {
        // -----------------------------------------------------------------
        // 1. Find the request row first – it is the *source of truth*
        // -----------------------------------------------------------------
        $accessReq = AccessRequest::where('request_token', $token)
            ->first();

        if (! $accessReq) {
            return $this->redirectError('invalid', 'Invalid or unknown token.');
        }

        // -----------------------------------------------------------------
        // 2. Already processed?
        // -----------------------------------------------------------------
        if ($accessReq->user_id) {
            return $this->redirectError('already_used', 'This link has already been used.');
        }

        // -----------------------------------------------------------------
        // 3. Get payload – first from cache, then from DB column fallback
        // -----------------------------------------------------------------
        $cacheKey = "registration:{$token}";
        $payload  = Cache::get($cacheKey);

        if (! $payload && $accessReq->payload) {
            // Fallback – we stored a JSON copy in the row (see register() tweak)
            $payload = json_decode($accessReq->payload, true);
        }

        if (! $payload) {
            return $this->redirectError('expired', 'Verification link expired. Please register again.');
        }

        // -----------------------------------------------------------------
        // 4. Validate payload *before* any DB write
        // -----------------------------------------------------------------
        try {
            $this->validatePayload($payload);
        } catch (\Throwable $e) {
            Log::warning('Registration payload invalid', ['token' => $token, 'error' => $e->getMessage()]);
            return $this->redirectError('invalid', 'Submitted data is incomplete. Please register again.');
        }

        // -----------------------------------------------------------------
        // 5. Transaction – every DB write inside its own mini‑try
        // -----------------------------------------------------------------
        DB::beginTransaction();
        try {
            $user = $this->createUser($payload);
            $profile = $this->createProfile($user, $payload);
            $this->createFirm($profile, $payload);
            $this->createComplianceAck($user, $payload);
            // $this->finalizeAccessRequest($accessReq, $user);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            // ---- LOG THE EXACT REASON ---------------------------------
            Log::error('Registration verification failed', [
                'token'   => $token,
                'user_id' => $accessReq->user_id ?? null,
                'exception' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // ---- USER‑FRIENDLY MESSAGE --------------------------------
            return $this->redirectError('failed', 'Something went wrong while creating your account. Our team has been notified.');
        }

        // -----------------------------------------------------------------
        // 6. Clean‑up
        // -----------------------------------------------------------------
        Cache::forget($cacheKey);
        // (optional) $accessReq->delete(); – keep for audit

        // -----------------------------------------------------------------
        // 7. Success → welcome mail + redirect
        // -----------------------------------------------------------------
        Mail::to($user->email)->queue(new WelcomePendingApprovalMail($user, $profile ?? null));

        // Notify all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(new NewRegistrationMail($user));
        }

        return redirect()->away(
            "https://pinnaclealts.com/?verified=success&status=pending_approval"
        );
    }


    private function redirectError(string $code, string $message): RedirectResponse
    {
        return redirect("https://pinnaclealts.com/?error={$code}")
            ->with('error', $message);
    }

    private function validatePayload(array $payload): void
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'firm_name' => 'required|string',
            'phone'    => 'required|string',
            'country'  => 'required|string',
            'investor_type' => 'required|in:ria_adviser,broker_dealer,family_office,institutional,fund_manager,other',
        ];

        $validator = Validator::make($payload, $rules);
        if ($validator->fails()) {
            throw new \Exception('Payload validation failed: ' . json_encode($validator->errors()->all()));
        }
    }

    private function createUser(array $payload): User
    {
        return User::create([
            'email'           => $payload['email'],
            'password'        => Hash::make($payload['password']),
            'email_verified_at' => now(),
            'role'            => 'user',
            'access_level'    => 'review',
            'is_active'       => false,
        ]);
    }

    private function createProfile(User $user, array $payload): Profiles
    {
        return Profiles::create([
            'user_id'           => $user->id,
            'first_name'        => $payload['first_name'],
            'last_name'         => $payload['last_name'],
            'firm_name'         => $payload['firm_name'],
            'phone'             => $payload['phone'],
            'country'           => $payload['country'],
            'investor_type'     => $payload['investor_type'],
            'investor_type_other' => $payload['investor_type'] === 'other'
                ? ($payload['investor_type_other'] ?? null)
                : null,
        ]);
    }

    private function createFirm(Profiles $profile, array $payload): Firm
    {
        return Firm::create([
            'profile_id'                     => $profile->id,
            'is_registered'                  => (bool)($payload['is_registered'] ?? false),
            'firm_crd'                       => $payload['firm_crd'] ?? null,
            'individual_crd'                 => $payload['individual_crd'] ?? null,
            'firm_aum_min'                   => $payload['firm_aum_min'] ?? null,
            'firm_aum_max'                   => $payload['firm_aum_max'] ?? null,
            'address'                        => $payload['address'] ?? null,
            'explanation_if_not_registered' => $payload['explain_not_registered'] ?? null,
        ]);
    }

    private function createComplianceAck(User $user, array $payload): void
    {
        $now = now();
        ComplianceAcknowledgment::create([
            'user_id'                => $user->id,
            'terms_agreed'           => true,
            'terms_agreed_at'        => $now,
            'privacy_agreed'         => true,
            'privacy_agreed_at'      => $now,
            'investor_acknowledgment' => true,
            'investor_acknowledgment_at' => $now,
            'confidentiality_agreed' => true,
            'confidentiality_agreed_at' => $now,
            'marketing_opt_in'       => $payload['marketing_opt_in'] ?? false,
            'marketing_opt_in_at'    => ($payload['marketing_opt_in'] ?? false) ? $now : null,
            'ip_address'             => $payload['ip_address'] ?? null,
            'user_agent'             => $payload['user_agent'] ?? null,
        ]);
    }

    private function finalizeAccessRequest(AccessRequest $req, User $user): void
    {
        $req->update([
            'user_id' => $user->id,
            // 'status'  => 'review',
        ]);
    }
}
