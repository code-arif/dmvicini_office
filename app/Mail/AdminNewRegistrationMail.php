<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminNewRegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user->load('profile');
    }

    public function build()
    {
        return $this->subject('New Registration: ' . $this->user->profile->first_name . ' ' . $this->user->profile->last_name)
            ->markdown('emails.admin.new_registration');
    }
}
