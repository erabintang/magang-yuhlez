<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $internName,
        public string $programTitle,
        public string $status,
        public ?string $reason = null,
        public ?string $companyName = null,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->status === 'ACCEPTED'
            ? 'YUHLEZ - Pendaftaran Magang Anda Diterima'
            : 'YUHLEZ - Hasil Pendaftaran Magang';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    protected function buildHtml(): string
    {
        $accepted = $this->status === 'ACCEPTED';
        $action = $accepted ? 'Diterima' : 'Ditolak';
        $companyPart = $this->companyName ? " oleh <b>{$this->companyName}</b>" : '';

        if ($accepted) {
            $bodyIntro = "Selamat, <b>{$this->internName}</b>! Pendaftaran kamu untuk program <b>{$this->programTitle}</b>{$companyPart} telah <b>diterima</b>.";
            $nextSteps = '<p>Langkah berikutnya: periksa dashboard YUHLEZ untuk detail program, jadwal, dan informasi dari perusahaan. Pantau juga notifikasi di dalam aplikasi.</p>';
        } else {
            $bodyIntro = "Mohon maaf, <b>{$this->internName}</b>. Pendaftaran kamu untuk program <b>{$this->programTitle}</b>{$companyPart} telah <b>ditolak</b>.";
            $nextSteps = '<p>Jangan berkecil hati - kamu tetap dapat mencoba program magang lain yang tersedia di YUHLEZ.</p>';
        }

        $reasonHtml = $this->reason ? "<p>Alasan: {$this->reason}</p>" : '';
        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $urlHtml = "<p><a href=\"{$url}/dashboard/intern/applications\">Buka status pendaftaran di YUHLEZ</a></p>";

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Program Magang</h2>
            <p>{$bodyIntro}</p>
            {$reasonHtml}
            {$nextSteps}
            {$urlHtml}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
