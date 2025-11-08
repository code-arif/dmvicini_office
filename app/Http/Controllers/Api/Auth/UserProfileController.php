<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Helper\Helper;
use App\Traits\ApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    use ApiResponse;

    // user profile
    public function me(Request $request): JsonResponse
    {
        try {
            $user = auth('api')->user();

            if (! $user) {
                return $this->error([], 'User not authenticated.', 401);
            }

            // Load all needed relations in one go
            $user->loadMissing(['profile.firm', 'accessRequest', 'complianceAcknowledgment']);

            if (! $user->is_active) {
                return $this->error([], 'Your account has been deactivated.', 403);
            }

            // Use the full UserResource (includes profile + firm)
            $resource = new UserResource($user);

            // Convert to array first so we can merge extra fields
            $data = $resource->toArray($request);

            // Add access request status
            if ($user->accessRequest) {
                $data['access_request'] = [
                    'status'       => $user->accessRequest->status,
                    'verified_at'  => $user->accessRequest->verified_at?->toISOString(),
                ];
            }

            // Add provisional access info
            if ($user->access_level === 'provisional' && $user->provisional_expires_at) {
                $data['provisional_expires_at'] = $user->provisional_expires_at->toISOString();
                $data['provisional_days_remaining'] = now()->diffInDays($user->provisional_expires_at, false);
            }

            // Optional: Add compliance acknowledgment flag
            if ($user->complianceAcknowledgment) {
                $data['compliance_acknowledged'] = true;
                $data['compliance_acknowledged_at'] = $user->complianceAcknowledgment->created_at?->toISOString();
            }

            return $this->success($data, 'User details retrieved successfully.', 200);
        } catch (\Throwable $e) {
            Log::error('Get User (me) Error: ' . $e->getMessage(), [
                'user_id' => auth('api')->id(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return $this->error([], 'Failed to retrieve user details.', 500);
        }
    }

    // update profile
    public function updateProfile(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        // -----------------------------------------------------------------
        // 1. Validation (avatar + all profile fields)
        // -----------------------------------------------------------------
        $validator = Validator::make($request->all(), [
            // ----- User ----------------------------------------------------
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],

            // ----- Profile -------------------------------------------------
            'first_name'            => ['required', 'string', 'max:255'],
            'last_name'             => ['required', 'string', 'max:255'],
            'title'                 => ['nullable', 'string', 'max:255'],
            'firm_name'             => ['required', 'string', 'max:255'],
            'phone'                 => ['required', 'string'],
            'country'               => ['required', 'string', 'max:2'],
            'investor_type'         => ['nullable', 'string'],
            'investor_type_other'   => ['nullable'],
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        // -----------------------------------------------------------------
        // 2. Transaction – everything or nothing
        // -----------------------------------------------------------------
        DB::beginTransaction();
        try {
            // ----- Avatar handling (User table) -------------------------
            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Helper::deleteImage($user->avatar);
                }
                $data['avatar'] = Helper::uploadImage($request->file('avatar'), 'profile');
            } else {
                unset($data['avatar']);
            }

            // Update User (only avatar for now)
            $user->update(Arr::only($data, ['avatar']));

            // ----- Profile handling --------------------------------------
            $profile = $user->profile; // assuming `profile()` relation on User model
            if (! $profile) {
                // safety net – create if missing (should never happen)
                $profile = $user->profile()->create([]);
            }

            $profileFields = [
                'first_name',
                'last_name',
                'title',
                'firm_name',
                'phone',
                'country',
                'investor_type',
                'investor_type_other',
            ];
            $profile->update(Arr::only($data, $profileFields));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'exception' => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return $this->error([], 'Failed to update profile.', 500);
        }

        // -----------------------------------------------------------------
        // 3. Return fresh resource
        // -----------------------------------------------------------------
        return $this->success(new UserResource($user->fresh(['profile'])), 'Profile updated successfully.', 200);
    }


    // update avatar
    public function updateAvatar(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first(), 422);
        }

        try {
            if ($user->avatar) {
                Helper::deleteImage($user->avatar);
            }

            $path = Helper::uploadImage($request->file('avatar'), 'profile');
            $user->update(['avatar' => $path]);
        } catch (\Throwable $e) {
            Log::error('Avatar update error', [
                'user_id'   => $user->id,
                'exception' => $e->getMessage(),
            ]);
            return $this->error([], 'Failed to update avatar.', 500);
        }

        return $this->success(new UserResource($user), 'Avatar updated successfully.', 200);
    }


    // update firm
    public function updateFirm(Request $request): JsonResponse
    {
        $user    = auth('api')->user();
        $profile = $user->profile;

        if (! $profile) {
            return $this->error([], 'Profile not found.', 404);
        }

        $firm = $profile->firm; // assuming `firm()` relation on Profile model

        $validator = Validator::make($request->all(), [
            'is_registered'                 => ['required', 'boolean'],
            'firm_crd'                      => ['nullable', 'string', 'size:7'],
            'individual_crd'                => ['nullable', 'string', 'size:7'],
            'firm_aum'                      => ['nullable', 'integer', 'min:0'],
            'address'                       => ['nullable', 'string'],
            'explanation_if_not_registered' => ['nullable', 'required_if:is_registered,0', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {
            // create firm row if it does not exist yet
            if (! $firm) {
                $firm = $profile->firm()->create([]);
            }

            $firm->update($validator->validated());

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Firm update failed', [
                'user_id'   => $user->id,
                'profile_id' => $profile->id,
                'exception' => $e->getMessage(),
            ]);
            return $this->error([], 'Failed to update firm information.', 500);
        }

        return $this->success($firm, 'Firm information updated successfully.', 200);
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
