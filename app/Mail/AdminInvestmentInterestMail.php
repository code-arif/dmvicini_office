<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Investment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminInvestmentInterestMail extends Mailable
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
            subject: "Notice from Alt’s Platform: Interest in {$this->investment->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.investment_interest_notification',
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
