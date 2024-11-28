<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $actionURL;

    public function __construct($actionURL)
    {
        $this->actionURL = $actionURL;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Email | GiveNow',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $emailContent = [
            'subject' => 'Verify Email | GiveNow',
            'body' => 'Please confirm your account.
                        Thank you for registering. To complete your registration, please click the button below:',
            'button_title' => 'CONFIRM ACCOUNT',
        ];

        return new Content(
            view: 'mail.verify-email',
            with: [
                'actionURL' => $this->actionURL,
                'emailContent' => $emailContent,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}