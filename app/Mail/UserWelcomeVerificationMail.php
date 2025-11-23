<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcomeVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verifyUrl;

    public function __construct(User $user, $verifyUrl)
    {
        $this->user = $user->load('profile');
        $this->verifyUrl = $verifyUrl;
    }

    public function build()
    {
        return $this->subject('New Registration: ' . $this->user->profile->first_name . ' ' . $this->user->profile->last_name)
            ->markdown('emails.user.user_welcome_verification_mail');
    }
}
