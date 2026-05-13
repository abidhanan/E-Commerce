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
            ->subject('Verifikasi Email Anda')
            ->view('Email.verify', [
                'verifyUrl' => $verificationUrl,
                'name' => $notifiable->name,
            ]);
    }
}