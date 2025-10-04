<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;
    public int $otp;
    public User $user;
    /**
     * Create a new message instance.
     */
    public function __construct(int $otp, User $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }


    public function build()
    {
        return $this->subject('Your OTP for Email Verification')
            ->view('mail.passResetOtp')
            ->with(['otp' => $this->otp]);
    }
}
