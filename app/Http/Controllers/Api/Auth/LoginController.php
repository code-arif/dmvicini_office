<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Reverb\Protocols\Pusher\Http\Controllers\Controller;

class LoginController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        // 1. User exists?
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // 2. Email verified?
        if (!$user->hasVerifiedEmail()) {
            return $this->error([], 'Please verify your email first. Check your inbox for the verification link.', 403);
        }

        // 4. Generate JWT Token
        $token = auth('api')->login($user);

        // 5. Update last login
        $user->update(['last_login_at' => now()]);

        // 6. Prepare response
        return $this->success([
            'user' => [
                'id'            => $user->id,
                'email'         => $user->email,
                'role'          => $user->role,
                'access_level'  => $user->access_level,
                'is_active'     => $user->is_active,
                'email_verified' => true,
                'profile'       => $user->profile ? [
                    'first_name' => $user->profile->first_name,
                    'last_name'  => $user->profile->last_name,
                    'full_name'  => $user->profile->full_name,
                    'firm_name'  => $user->profile->firm_name,
                    'phone'      => $user->profile->phone,
                    'country'    => $user->profile->country,
                ] : null,
            ],
            'token' => $token,
            'token_type'   => 'bearer',
        ], 'Login successful! Welcome back.');
    }

    public function logout()
    {
        auth('api')->logout();
        return $this->success([], 'Successfully logged out.');
    }

    // public function refresh()
    // {
    //     $user = auth('api')->user();

    //     // Extra safety: re-check status on refresh
    //     if (!$user->hasVerifiedEmail() || !$user->is_active) {
    //         auth('api')->logout();
    //         return $this->error([], 'Account access restricted. Please contact support.', 403);
    //     }

    //     return $this->success([
    //         'access_token' => auth('api')->refresh(),
    //         'token_type'   => 'bearer',
    //         'expires_in'   => auth('api')->factory()->getTTL() * 60,
    //     ], 'Token refreshed successfully.');
    // }

    // Optional: me endpoint
    // public function me()
    // {
    //     return $this->success(auth('api')->user()->load('profile'));
    // }
}
