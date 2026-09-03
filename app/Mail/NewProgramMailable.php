<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewProgramMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $internName,
        public string $programTitle,
        public string $companyName,
        public string $programSlug,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "YUHLEZ - Program Magang Baru: {$this->programTitle}");
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $urlHtml = "<p><a href=\"{$url}/magang/{$this->programSlug}\">Lihat Program {$this->programTitle}</a></p>";

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Program Magang Baru</h2>
            <p>Halo <b>{$this->internName}</b>,</p>
            <p>Perusahaan <b>{$this->companyName}</b> baru saja membuka program magang baru:</p>
            <p style="font-size:18px;font-weight:bold">{$this->programTitle}</p>
            <p>Segera daftar sebelum pendaftaran ditutup!</p>
            {$urlHtml}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
