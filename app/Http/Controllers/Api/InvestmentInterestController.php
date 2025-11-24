<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\InterestedUser;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvestmentInterestMail;
use App\Mail\AdminInvestmentInterestMail;

class InvestmentInterestController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $request->validate([
            'investment_id' => 'required|exists:investments,id',
        ]);

        $user = auth()->user();

        if (!$user) {
            return $this->error([], "Unauthorized.", 401);
        }

        $userId = $user->id;
        $email = $user->email;
        $investmentId = $request->investment_id;
        $adminEmail = User::where('role', 'admin')->select('email')->first();

        try {

            $interest = InterestedUser::updateOrCreate(
                [
                    'user_id' => $userId,
                    'investment_id' => $investmentId,
                ],
                [
                    'email' => $email,
                ]
            );

            // =============================
            // Email will be sent here
            // =============================
            Mail::to($adminEmail->email)->send(new AdminInvestmentInterestMail($user, $investmentId));
            Mail::to($email)->send(new UserInvestmentInterestMail($user, $investmentId));

            return $this->success($interest, "Interest saved successfully.", 201);
        } catch (Exception $e) {

            Log::error('Investment interest failed', [
                'user_id' => $userId,
                'investment_id' => $investmentId,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return $this->error([], "Something went wrong. Please try again later.", 500);
        }
    }
}
