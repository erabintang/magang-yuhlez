<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateIssuedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $internName,
        public string $programTitle,
        public ?string $companyName = null,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'YUHLEZ - Sertifikat Magang Anda');
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        $companyPart = $this->companyName ? " di <b>{$this->companyName}</b>" : '';
        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $urlHtml = "<p><a href=\"{$url}/dashboard/intern/certificates\">Lihat & unduh sertifikat Anda di YUHLEZ</a></p>";

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Sertifikat Program</h2>
            <p>Selamat, <b>{$this->internName}</b>!</p>
            <p>Sertifikat untuk program <b>{$this->programTitle}</b>{$companyPart} telah diterbitkan dan tersedia untuk Anda.</p>
            <p>Anda dapat mengunduh sertifikat melalui dashboard YUHLEZ Anda.</p>
            {$urlHtml}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
