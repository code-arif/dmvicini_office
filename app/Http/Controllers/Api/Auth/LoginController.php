<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use ApiResponse;
    /**
     * Login with approval and access level check
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'min:8'],
            ]);

            if ($validator->fails()) {
                return $this->error([], $validator->errors()->first(), 422);
            }

            $credentials = $validator->validated();
            $user = User::with(['profile', 'accessRequest'])->where('email', $credentials['email'])->first();

            // Check if user exists
            if (!$user) {
                return $this->error([], 'Email is incorrect or not found in our database.', 404);
            }

            // Check password
            if (!Hash::check($credentials['password'], $user->password)) {
                return $this->error([], 'Password is incorrect.', 401);
            }

            // Check if account is active
            // if (!$user->is_active) {
            //     return $this->error([], 'Your account is not yet activated. Please wait for admin approval.', 403);
            // }

            // Check access level and provide appropriate messages
            switch ($user->access_level) {
                case 'review':
                    return $this->error([], 'Your account is under review. Please wait for admin approval.', 403);

                case 'limited':
                    // Allow login but with limited message
                    $limitedMessage = 'You have limited access. Some features may be restricted.';
                    break;

                case 'provisional':
                    // Check if provisional access has expired
                    if ($user->provisional_expires_at && now()->greaterThan($user->provisional_expires_at)) {
                        // Update user status
                        $user->update([
                            'is_active' => false,
                            'access_level' => 'review'
                        ]);
                        return $this->error([], 'Your provisional access has expired. Please contact admin for full access.', 403);
                    }
                    // $limitedMessage = 'You have provisional access until ' . $user->provisional_expires_at->format('M d, Y');
                    break;

                case 'full':
                    $limitedMessage = null; // Full access, no message
                    break;

                default:
                    return $this->error([], 'Invalid access level. Please contact support.', 403);
            }

            // Attempt to generate JWT token
            if (!$token = auth('api')->attempt($credentials)) {
                return $this->error([], 'Invalid email or password.', 401);
            }

            // Update last login
            $user->update([
                'last_login_at' => now(),
            ]);

            // Prepare user data
            $userData = [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'access_level' => $user->access_level,
                'is_active' => $user->is_active,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ];

            // Add profile data if exists
            if ($user->profile) {
                $userData['profile'] = [
                    'first_name' => $user->profile->first_name,
                    'last_name' => $user->profile->last_name,
                    'full_name' => $user->profile->full_name,
                    'firm_name' => $user->profile->firm_name,
                    'investor_type' => $user->profile->investor_type,
                ];
            }

            // Add provisional expiry if applicable
            if ($user->access_level === 'provisional' && $user->provisional_expires_at) {
                $userData['provisional_expires_at'] = $user->provisional_expires_at->toISOString();
            }

            $message = 'Successfully Logged In';
            if (isset($limitedMessage)) {
                $message .= '. ' . $limitedMessage;
            }

            return $this->success($userData, $message, 200);
        } catch (Exception $e) {
            Log::error('Login Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->error([], 'An error occurred during login. Please try again.', 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        try {
            // Get the authenticated user
            $user = auth('api')->user();

            if (!$user) {
                return $this->error([], 'User not authenticated.', 401);
            }

            // Log the logout action
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'logout_at' => now()
            ]);

            // Invalidate the token
            auth('api')->logout();

            // Optional: Clear any session data if you're using sessions
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return $this->success([], 'Successfully logged out.', 200);
        } catch (Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->error([], 'An error occurred during logout.', 500);
        }
    }


    /**
     * Refresh JWT token
     */
    public function refresh(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return $this->error([], 'User not authenticated.', 401);
            }

            // Check if user is still active
            if (!$user->is_active) {
                auth('api')->logout();
                return $this->error([], 'Your account has been deactivated. Please contact admin.', 403);
            }

            // Check access level
            if ($user->access_level === 'review') {
                auth('api')->logout();
                return $this->error([], 'Your account is under review. Access has been revoked.', 403);
            }

            // Check provisional expiry
            if ($user->access_level === 'provisional' && $user->provisional_expires_at) {
                if (now()->greaterThan($user->provisional_expires_at)) {
                    $user->update([
                        'is_active' => false,
                        'access_level' => 'review'
                    ]);
                    auth('api')->logout();
                    return $this->error([], 'Your provisional access has expired.', 403);
                }
            }

            // Refresh the token
            $newToken = auth('api')->refresh();

            $data = [
                'token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ];

            return $this->success($data, 'Token refreshed successfully.', 200);
        } catch (Exception $e) {
            Log::error('Token Refresh Error: ' . $e->getMessage());
            return $this->error([], 'Failed to refresh token.', 500);
        }
    }
}
