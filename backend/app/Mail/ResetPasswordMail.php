<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $actionURL;
    public $password;

    public function __construct($actionURL,$password)
    {
        $this->actionURL = $actionURL;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Request | GiveNow',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $emailContent = [
            'subject' => 'Password Reset Request | GiveNow',
            'body' => $this->password,
            'button_title' => 'BACK TO LOGIN',
        ];
        
        return new Content(
            view: 'mail.change-password',
            with: [
                'actionURL' => $this->actionURL,
                'emailContent' => $emailContent,
                'password' => $this->password,
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