<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead)
    {
    }

    public function build(): self
    {
        return $this->subject('New Contact Form Submission - ' . $this->lead->name)
            ->view('emails.lead-notification')
            ->with([
                'lead' => $this->lead,
            ]);
    }
}
