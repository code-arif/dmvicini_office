<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $profile;
    public $firm;
    public $approveUrl;
    public $rejectUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->profile = $user->profile;
        // $this->firm = $this->profile?->firm;

        // Secure token-based URLs
        $token = encrypt($user->id);
        $this->approveUrl = route('admin.approve', ['token' => $token]);
        $this->rejectUrl  = route('admin.reject', ['token' => $token]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New User Registration – Review Required',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.new-registration',
        );
    }
}
