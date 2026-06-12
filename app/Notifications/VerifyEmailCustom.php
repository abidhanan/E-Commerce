<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailCustom extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        // 1. Membuat URL Verifikasi yang dienkripsi (Bawaan Laravel)
        $verificationUrl = $this->verificationUrl($notifiable);

        // 2. Mengirim URL tersebut ke template email yang baru saja kamu buat
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Verify Email Address - Clothique')
            // Sesuaikan 'emails.verify' dengan nama folder dan file blade emailmu!
            // Jika filemu ada di folder resources/views/email/verify.blade.php, maka tulis 'email.verify'
            ->view('email.verify', ['url' => $verificationUrl]);
    }
}