<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Responsibility: describe the email (subject, body view, PDF attachment). Does not send it. */
class CustomerAccountReportMail extends Mailable
{
    public function __construct(
        public string $mailSubject,
        public string $messageText,
        public string $customerName,
        public string $pdfContent,
        public string $pdfName,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->mailSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.customer-account-report');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, $this->pdfName)
                ->withMime('application/pdf'),
        ];
    }
}