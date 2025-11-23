<?php

namespace App\Mail;

use App\Models\Profiles;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public int $otp;
    public User $user;

    public function __construct(int $otp, User $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Your Password Reset OTP - Pinnacle Alts')
            ->view('emails.user.passResetOtp')
            ->with([
                'otp'     => $this->otp,
                'user'    => $this->user,
                'profile' => $this->user->profile,
            ]);
    }
}
