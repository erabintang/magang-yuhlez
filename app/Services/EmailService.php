<?php

namespace App\Services;

use App\Mail\ApplicationStatusMailable;
use App\Mail\ApplicationSubmittedMailable;
use App\Mail\AccountCreatedMailable;
use App\Mail\CertificateIssuedMailable;
use App\Mail\NewProgramMailable;
use App\Mail\ProgramUpdatedMailable;
use App\Mail\WorkCreatedMailable;
use App\Mail\WorkSubmissionReceivedMailable;
use App\Mail\WorkSubmissionStatusMailable;
use App\Mail\PasswordResetMailable;
use App\Mail\PasswordChangedMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send application status email (ACCEPTED/REJECTED).
     */
    public static function sendApplicationStatusEmail(
        string $toEmail,
        string $internName,
        string $programTitle,
        string $status,
        ?string $reason = null,
        ?string $companyName = null
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new ApplicationStatusMailable(
                    internName: $internName,
                    programTitle: $programTitle,
                    status: $status,
                    reason: $reason,
                    companyName: $companyName,
                )
            );

            Log::info('EmailService: Application status email queued', [
                'to' => $toEmail,
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue application status email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send certificate issued email.
     */
    public static function sendCertificateIssuedEmail(
        string $toEmail,
        string $internName,
        string $programTitle,
        ?string $companyName = null
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new CertificateIssuedMailable(
                    internName: $internName,
                    programTitle: $programTitle,
                    companyName: $companyName,
                )
            );

            Log::info('EmailService: Certificate issued email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue certificate email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send account created email by root/admin.
     */
    public static function sendAccountCreatedEmail(
        string $toEmail,
        string $name,
        string $role,
        ?string $password = null
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new AccountCreatedMailable(
                    name: $name,
                    role: $role,
                    password: $password,
                )
            );

            Log::info('EmailService: Account created email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue account created email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send password reset email.
     */
    public static function sendPasswordResetEmail(
        string $toEmail,
        string $name,
        string $resetUrl
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new PasswordResetMailable(
                    name: $name,
                    resetUrl: $resetUrl,
                )
            );

            Log::info('EmailService: Password reset email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue password reset email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send password changed notification.
     */
    public static function sendPasswordChangedEmail(
        string $toEmail,
        string $name
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new PasswordChangedMailable(name: $name)
            );

            Log::info('EmailService: Password changed email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue password changed email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send profile updated email.
     */
    public static function sendProfileUpdatedEmail(
        string $toEmail,
        string $name
    ): void {
        try {
            // Use a generic Mailable for profile update
            Mail::raw("Halo <b>{$name}</b>,<br><br>Profil akun kamu baru saja diperbarui di YUHLEZ.<br><br>Jika ini bukan kamu yang melakukannya, segera hubungi tim YUHLEZ.<br><br><a href=\"" . config('app.url', 'http://localhost:8000') . "/dashboard\">Buka dashboard YUHLEZ</a><br><br><p style=\"color:#888;font-size:12px\">Email ini dikirim otomatis oleh sistem YUHLEZ.</p>", function ($message) use ($toEmail) {
                $message->to($toEmail)
                    ->subject('YUHLEZ - Profil Anda Diperbarui')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('EmailService: Profile updated email sent', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to send profile updated email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send new program notification email to all interns.
     */
    public static function sendNewProgramEmail(
        string $toEmail,
        string $internName,
        string $programTitle,
        string $companyName,
        string $programSlug
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new NewProgramMailable(
                    internName: $internName,
                    programTitle: $programTitle,
                    companyName: $companyName,
                    programSlug: $programSlug,
                )
            );

            Log::info('EmailService: New program email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue new program email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send application submitted email to company.
     */
    public static function sendApplicationSubmittedEmail(
        string $toEmail,
        string $companyName,
        string $internName,
        string $programTitle,
        string $positionName
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new ApplicationSubmittedMailable(
                    companyName: $companyName,
                    internName: $internName,
                    programTitle: $programTitle,
                    positionName: $positionName,
                )
            );

            Log::info('EmailService: Application submitted email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue application submitted email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send program updated email to enrolled interns.
     */
    public static function sendProgramUpdatedEmail(
        string $toEmail,
        string $internName,
        string $programTitle,
        string $programSlug
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new ProgramUpdatedMailable(
                    internName: $internName,
                    programTitle: $programTitle,
                    programSlug: $programSlug,
                )
            );

            Log::info('EmailService: Program updated email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue program updated email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send new work created email to intern participants.
     */
    public static function sendWorkCreatedEmail(
        string $toEmail,
        string $internName,
        string $workTitle,
        string $companyName
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new WorkCreatedMailable(
                    internName: $internName,
                    workTitle: $workTitle,
                    companyName: $companyName,
                )
            );

            Log::info('EmailService: Work created email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue work created email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send work submission received email to company.
     */
    public static function sendWorkSubmissionReceivedEmail(
        string $toEmail,
        string $companyName,
        string $internName,
        string $submissionTitle,
        string $workTitle
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new WorkSubmissionReceivedMailable(
                    companyName: $companyName,
                    internName: $internName,
                    submissionTitle: $submissionTitle,
                    workTitle: $workTitle,
                )
            );

            Log::info('EmailService: Work submission received email queued', ['to' => $toEmail]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue work submission received email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send work submission status email (ACCEPTED/REJECTED) to intern.
     */
    public static function sendWorkSubmissionStatusEmail(
        string $toEmail,
        string $internName,
        string $submissionTitle,
        string $workTitle,
        string $status,
        ?string $reviewNote = null,
        ?string $companyName = null
    ): void {
        try {
            Mail::to($toEmail)->queue(
                new WorkSubmissionStatusMailable(
                    internName: $internName,
                    submissionTitle: $submissionTitle,
                    workTitle: $workTitle,
                    status: $status,
                    reviewNote: $reviewNote,
                    companyName: $companyName,
                )
            );

            Log::info('EmailService: Work submission status email queued', [
                'to' => $toEmail,
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            Log::error('EmailService: Failed to queue work submission status email', [
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
