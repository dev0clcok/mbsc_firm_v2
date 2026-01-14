<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead)
    {
    }

    public function build(): self
    {
        return $this->subject('Thank you for contacting Bangladesh Consultant')
            ->view('emails.contact-confirmation')
            ->with([
                'lead' => $this->lead,
            ]);
    }
}
