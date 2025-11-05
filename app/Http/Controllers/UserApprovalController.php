<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserApprovedMail;
use App\Mail\UserRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function approve($token)
    {
        try {
            $userId = decrypt($token);
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Invalid token.');
        }

        $user = User::findOrFail($userId);

        if ($user->is_active) {
            return redirect()->route('admin.dashboard')->with('info', 'User already approved.');
        }

        $user->update([
            'is_active' => true,
            'access_level' => 'full',
            'provisional_expires_at' => null,
        ]);

        // Update access request
        $user->accessRequest()->update(['status' => 'approved']);

        // Send welcome mail
        Mail::to($user->email)->queue(new UserApprovedMail($user));

        return redirect()->route('admin.dashboard')->with('success', 'User approved successfully.');
    }

    public function reject($token)
    {
        try {
            $userId = decrypt($token);
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Invalid token.');
        }

        $user = User::findOrFail($userId);

        $user->update([
            'is_active' => false,
            'access_level' => 'review',
        ]);

        $user->accessRequest()->update(['status' => 'rejected']);

        Mail::to($user->email)->queue(new UserRejectedMail($user));

        return redirect()->route('admin.dashboard')->with('success', 'User rejected.');
    }
}
