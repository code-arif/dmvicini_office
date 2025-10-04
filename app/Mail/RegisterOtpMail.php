<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp , $otpExpiresAt;

    public function __construct($otp )
    {
       $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Your OTP for Email Verification')
                    ->view('mail.verifyEmail')
                    ->with(['otp' => $this->otp]);
    }
}
