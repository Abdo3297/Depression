<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PatientForgetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user,$otp;
    public function __construct($user,$otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Patient Forget Password Mail',
        );
    }
    public function content(): Content
    {
        return new Content(
            view: 'mail.auth.forget',
        );
    }
    public function attachments(): array
    {
        return [];
    }
}
