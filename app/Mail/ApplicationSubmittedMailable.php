<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $companyName,
        public string $internName,
        public string $programTitle,
        public string $positionName,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "YUHLEZ - Pendaftar Baru: {$this->internName}");
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $urlHtml = "<p><a href=\"{$url}/dashboard/company/applications\">Lihat Pendaftaran di Dashboard</a></p>";

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Pendaftar Baru</h2>
            <p>Halo <b>{$this->companyName}</b>,</p>
            <p>Ada pendaftar baru untuk program magang Anda:</p>
            <p><b>{$this->internName}</b> mendaftar untuk posisi <b>{$this->positionName}</b> di program <b>{$this->programTitle}</b>.</p>
            <p>Silakan review dan terima atau tolak pendaftaran ini.</p>
            {$urlHtml}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
