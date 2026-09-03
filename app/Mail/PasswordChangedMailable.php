<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'YUHLEZ - Password Berhasil Diubah');
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $urlHtml = "<p><a href=\"{$url}/login\">Masuk ke YUHLEZ</a></p>";

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Password Diubah</h2>
            <p>Halo <b>{$this->name}</b>,</p>
            <p>Password akun YUHLEZ Anda telah berhasil diubah.</p>
            <p>Jika ini bukan Anda yang melakukannya, segera hubungi tim YUHLEZ.</p>
            {$urlHtml}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
