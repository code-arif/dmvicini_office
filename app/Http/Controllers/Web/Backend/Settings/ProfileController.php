<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use Exception;
use App\Models\User;
use App\Helper\Helper;
use App\Models\Profiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = User::with('profile')->find($request->id);
        return view('backend.layouts.settings.profile_settings', compact('user'));
    }

    //update admin profile
    public function UpdateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100|min:2',
            'last_name'  => 'nullable|string|max:100|min:2',
            'email'      => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail(auth()->id());

            // Update email
            $user->email = $request->email;
            $user->save();

            // make dummy profile
            $profile = Profiles::firstOrCreate(
                ['user_id' => $user->id],
                ['first_name' => 'David', 'last_name' => 'Vicini'] // default value
            );

            $profile->first_name = $request->first_name;
            $profile->last_name  = $request->last_name ?? '';
            $profile->save();

            DB::commit();

            session()->put('t-success', 'Profile updated successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            session()->put('t-error', 'Something went wrong!');
            Log::error('Profile Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // update admin password
    public function UpdatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password'     => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $user = Auth::user();
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect()->back()->with('t-success', 'Password updated successfully');
            } else {
                return redirect()->back()->with('t-error', 'Current password is incorrect');
            }
        } catch (Exception) {
            return redirect()->back()->with('t-error', 'Something went wrong');
        }
    }

    // update admin profile picture
    public function UpdateProfilePicture(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        try {
            $user      = Auth::user();
            $image     = $request->file('avatar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            //? Check if there's an existing profile picture
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                Helper::deleteImage(public_path($user->avatar));
            }

            //* Use the Helper class to handle the file upload
            $imagePath = Helper::uploadImage($image, 'profile', $imageName);

            if ($imagePath === null) {
                throw new Exception('Failed to upload image.');
            }

            //! Update user's avatar with the new image path
            $user->avatar = $imagePath;
            $user->save();

            return response()->json([
                'success'   => true,
                'image_url' => asset($imagePath),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
