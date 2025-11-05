<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $loginUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->loginUrl = config('app.frontend_url', 'https://pinnaclealts.com') . '/login';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Has Been Approved!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user.approved',
            with: [
                'user' => $this->user,
                'loginUrl' => $this->loginUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
