<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New website enquiry from '.$this->enquiry->name,
            replyTo: $this->enquiry->email
                ? [new Address($this->enquiry->email, $this->enquiry->name)]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.enquiry-received',
            with: [
                'enquiry' => $this->enquiry,
                'adminUrl' => url('/admin/enquiries'),
            ],
        );
    }
}
