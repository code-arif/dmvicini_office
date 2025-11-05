<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $retryUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->retryUrl = config('app.frontend_url', 'https://pinnaclealts.com') . '/register';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registration Update – Action Required',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user.rejected',
            with: [
                'user' => $this->user,
                'retryUrl' => $this->retryUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
