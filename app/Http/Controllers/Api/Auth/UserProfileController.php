<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Helper\Helper;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class UserProfileController extends Controller
{
    use ApiResponse;


    //get user progile
    /**
     * Get authenticated user details
     */
    public function me(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return $this->error([], 'User not authenticated.', 401);
            }

            // Load relationships
            $user->load(['profile.firm', 'accessRequest', 'complianceAcknowledgment']);

            // Check if still active
            if (!$user->is_active) {
                return $this->error([], 'Your account has been deactivated.', 403);
            }

            $userData = [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'access_level' => $user->access_level,
                'is_active' => $user->is_active,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'created_at' => $user->created_at->toISOString(),
                'last_login_at' => $user->last_login_at ? $user->last_login_at->toISOString() : null,
            ];

            // Add profile
            if ($user->profile) {
                $userData['profile'] = [
                    'first_name' => $user->profile->first_name,
                    'last_name' => $user->profile->last_name,
                    'full_name' => $user->profile->full_name,
                    'title' => $user->profile->title,
                    'firm_name' => $user->profile->firm_name,
                    'phone' => $user->profile->phone,
                    'country' => $user->profile->country,
                    'investor_type' => $user->profile->investor_type,
                ];

                // Add firm data
                if ($user->profile->firm) {
                    $userData['firm'] = [
                        'is_registered' => $user->profile->firm->is_registered,
                        'firm_crd' => $user->profile->firm->firm_crd,
                        'individual_crd' => $user->profile->firm->individual_crd,
                    ];
                }
            }

            // Add access request status
            if ($user->accessRequest) {
                $userData['access_request'] = [
                    'status' => $user->accessRequest->status,
                    'verified_at' => $user->accessRequest->verified_at ? $user->accessRequest->verified_at->toISOString() : null,
                ];
            }

            // Add provisional expiry if applicable
            if ($user->access_level === 'provisional' && $user->provisional_expires_at) {
                $userData['provisional_expires_at'] = $user->provisional_expires_at->toISOString();
                $userData['provisional_days_remaining'] = now()->diffInDays($user->provisional_expires_at, false);
            }

            return $this->success($userData, 'User details retrieved successfully.', 200);
        } catch (Exception $e) {
            Log::error('Get User Error: ' . $e->getMessage());
            return $this->error([], 'Failed to retrieve user details.', 500);
        }
    }

    // update profile
    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'avatar'       => ['nullable', 'image'],
            ]);

            if ($validator->fails()) {
                return $this->error([], $validator->errors()->first(), 422);
            }

            $user = auth('api')->user();

            $data = $validator->validated();

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Helper::deleteImage($user->avatar);
                }
                $avatarPath = Helper::uploadImage($request->file('avatar'), 'profile');
                $data['avatar'] = $avatarPath;
            }

            $user->update($data);

            return $this->success(new UserResource($user), 'Profile updated successfully.', 200);
        } catch (Exception $e) {

            Log::error('Profile Update Error: ' . $e->getMessage());
            return $this->error([], 'Failed to update profile.', 500);
        }
    }

    //update password
    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', 'string', 'min:8'],
                'password'  => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if ($validator->fails()) {
                return $this->error([], $validator->errors()->first(), 200);
            }

            $user = auth('api')->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->error([], 'current password is incorrect.', 200);
            }

            $user->update(['password' => Hash::make($request->password)]);

            return $this->success(['Password updated successfully'], 'Password updated successfully.', 200);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
