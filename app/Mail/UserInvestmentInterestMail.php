<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Investment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvestmentInterestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $investment;

    public function __construct(User $user, $investmentId)
    {
        $this->user = $user;
        $this->investment = Investment::findOrFail($investmentId);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Interest in "' . $this->investment->title . '" Has Been Recorded',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.investment_interest_confirmation',
            with: [
                'user' => $this->user,
                'investment' => $this->investment,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
