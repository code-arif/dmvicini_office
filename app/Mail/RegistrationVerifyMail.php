<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verifyUrl; // Public property
    public $expiresIn;
    public $appName;

    public function __construct($verifyUrl)
    {
        $this->verifyUrl = $verifyUrl;
        $this->expiresIn = 5; // minutes
    }

    public function build()
    {
        return $this->subject('Verify Your Email - ' . $this->appName)
            ->view('emails.verify_mail');
    }
}
