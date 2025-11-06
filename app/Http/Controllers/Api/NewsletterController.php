<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscriber;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterVerificationMail;
use Illuminate\Support\Facades\RateLimiter;

class NewsletterController extends Controller
{
    use ApiResponse;

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = $request->input('email');
        $ip = $request->ip();

        // Rate Limiting: 3 attempts per email per hour
        $emailKey = 'newsletter:email:' . $email;
        $ipKey = 'newsletter:ip:' . $ip;

        if (RateLimiter::tooManyAttempts($emailKey, 3)) {
            $seconds = RateLimiter::availableIn($emailKey);
            return $this->error([], "Too many attempts. Try again in " . ceil($seconds / 60) . " minutes.", 429);
        }

        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            return $this->error([], "Too many attempts from this IP.", 429);
        }

        // Check if already subscribed
        $existing = Subscriber::where('email', $email)->first();
        if ($existing) {
            if ($existing->is_verified) {
                return $this->error([], "You're already subscribed!", 409);
            } else {
                return $this->error([], "Please check your email to verify your subscription.", 409);
            }
        }

        try {
            // Create subscriber
            $subscriber = Subscriber::create([
                'email' => $email,
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
            ]);

            // Generate verification token
            $token = Str::random(60);
            cache()->put("newsletter_verify:{$token}", $subscriber->id, now()->addHours(24));

            // Send verification email
            $verifyUrl = route('newsletter.verify', ['token' => $token]);
            Mail::to($email)->send(new NewsletterVerificationMail($verifyUrl, $email));

            // Hit rate limiter
            RateLimiter::hit($emailKey, 3600);
            RateLimiter::hit($ipKey, 3600);

            return $this->success([], 'Subscription successful! Please check your email to verify.', 201);
        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return $this->error([], 'Failed to subscribe. Please try again later.', 500);
        }
    }

    // Optional: Verify endpoint
    public function verify($token)
    {
        $subscriberId = cache()->get("newsletter_verify:{$token}");
        if (!$subscriberId) {
            return redirect()->to('/?newsletter=expired');
        }

        $subscriber = Subscriber::find($subscriberId);
        if (!$subscriber) {
            return redirect()->to('/?newsletter=invalid');
        }

        $subscriber->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        cache()->forget("newsletter_verify:{$token}");

        return redirect()->to('/?newsletter=success');
    }
}
