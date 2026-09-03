<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreatedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $role,
        public ?string $password = null,
        public string $dashboardUrl = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'YUHLEZ - Akun Anda Telah Dibuat');
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    protected function buildHtml(): string
    {
        $roleLabel = match($this->role) {
            'COMPANY' => 'Perusahaan',
            'INTERN' => 'Intern',
            default => $this->role,
        };

        $url = $this->dashboardUrl ?: config('app.url', 'http://localhost:8000');
        $loginUrl = "<p><a href=\"{$url}/login\">Masuk ke YUHLEZ</a></p>";

        $passwordHtml = $this->password
            ? "<p>Password Anda: <b>{$this->password}</b></p><p><i>Harap segera ganti password setelah login pertama kali.</i></p>"
            : '';

        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:auto">
            <h2>YUHLEZ - Akun Baru</h2>
            <p>Halo <b>{$this->name}</b>,</p>
            <p>Akun Anda sebagai <b>{$roleLabel}</b> di YUHLEZ telah berhasil dibuat oleh administrator.</p>
            {$passwordHtml}
            <p>Silakan masuk untuk mulai menggunakan YUHLEZ.</p>
            {$loginUrl}
            <p style="color:#888;font-size:12px">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>
        </div>
        HTML;
    }
}
