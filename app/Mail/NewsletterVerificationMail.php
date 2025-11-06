<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verifyUrl;
    public $email;

    public function __construct($verifyUrl, $email)
    {
        $this->verifyUrl = $verifyUrl;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Verify Your Newsletter Subscription')
            ->view('emails.newsletter.verify')
            ->with([
                'verifyUrl' => $this->verifyUrl,
                'email' => $this->email,
            ]);
    }
}
