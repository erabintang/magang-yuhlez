<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProgramUpdatedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $internName,
        public string $programTitle,
        public string $programSlug,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "YUHLEZ - Program Magang Diperbarui: {$this->programTitle}");
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $urlHtml = "<p><a href=\"{$url}/magang/{$this->programSlug}\">Lihat Perubahan Program</a></p>";

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Program Diperbarui</h2>
            <p>Halo <b>{$this->internName}</b>,</p>
            <p>Program magang <b>{$this->programTitle}</b> yang kamu ikuti baru saja diperbarui.</p>
            <p>Silakan cek detail terbaru di dashboard YUHLEZ.</p>
            {$urlHtml}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
