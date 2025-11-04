<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Profiles;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomePendingApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $profile;
    public $appName;

    public function __construct(User $user, Profiles $profile)
    {
        $this->user = $user;
        $this->profile = $profile;
        $this->appName = config('app.name');
    }

    public function build()
    {
        return $this->subject('Welcome to ' . $this->appName . ' - Account Under Review')
            ->view('emails.welcome_pending_approval');
    }
}
