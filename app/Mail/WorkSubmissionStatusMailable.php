<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkSubmissionStatusMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $internName,
        public string $submissionTitle,
        public string $workTitle,
        public string $status,
        public ?string $reviewNote = null,
        public ?string $companyName = null,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        $action = $this->status === 'ACCEPTED' ? 'Diterima' : 'Ditolak';
        return new Envelope(subject: "YUHLEZ - Karya Anda {$action}");
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        $accepted = $this->status === 'ACCEPTED';
        $companyPart = $this->companyName ? " oleh <b>{$this->companyName}</b>" : '';

        if ($accepted) {
            $bodyIntro = "Selamat, <b>{$this->internName}</b>! Karya Anda \"{$this->submissionTitle}\" untuk \"{$this->workTitle}\"{$companyPart} telah <b>diterima</b>.";
        } else {
            $bodyIntro = "Mohon maaf, <b>{$this->internName}</b>. Karya Anda \"{$this->submissionTitle}\" untuk \"{$this->workTitle}\"{$companyPart} telah <b>ditolak</b>.";
        }

        $noteHtml = $this->reviewNote ? "<p>Catatan: {$this->reviewNote}</p>" : '';
        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $urlHtml = "<p><a href=\"{$url}/dashboard/intern/submissions\">Lihat Status Karya di YUHLEZ</a></p>";

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Status Karya</h2>
            <p>{$bodyIntro}</p>
            {$noteHtml}
            {$urlHtml}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
