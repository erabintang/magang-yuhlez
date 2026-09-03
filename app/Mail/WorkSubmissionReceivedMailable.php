<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkSubmissionReceivedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $companyName,
        public string $internName,
        public string $submissionTitle,
        public string $workTitle,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "YUHLEZ - Karya Baru dari {$this->internName}");
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $urlHtml = "<p><a href=\"{$url}/dashboard/company/submissions\">Lihat Karya di Dashboard</a></p>";

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Karya Baru dari Intern</h2>
            <p>Halo <b>{$this->companyName}</b>,</p>
            <p>Intern <b>{$this->internName}</b> mengirim karya baru:</p>
            <p style="font-size:18px;font-weight:bold">{$this->submissionTitle}</p>
            <p>Untuk karya: <b>{$this->workTitle}</b></p>
            <p>Silakan review dan terima atau tolak karya ini.</p>
            {$urlHtml}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
