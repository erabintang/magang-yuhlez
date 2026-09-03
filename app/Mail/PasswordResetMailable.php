<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $resetUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'YUHLEZ - Reset Password');
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Reset Password</h2>
            <p>Halo <b>{$this->name}</b>,</p>
            <p>Kami menerima permintaan untuk mengatur ulang password akun YUHLEZ Anda.</p>
            <p><a href="{$this->resetUrl}" style="display:inline-block;padding:12px 24px;background-color:#eab308;color:#000;text-decoration:none;border-radius:8px;font-weight:600">Atur Ulang Password</a></p>
            <p style="color:#666;font-size:13px">Tautan ini berlaku selama 1 jam. Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
