<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailCustom extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email - Clothique')
            // 1. Arahkan ke template EMAIL yang benar
            // 2. Samakan nama variabel menjadi 'url'
            ->view('Email.verify', [
                'url' => $verificationUrl, 
                'name' => $notifiable->name,
            ]);
    }
}