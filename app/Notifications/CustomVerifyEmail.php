<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmailNotification
{
    public function toMail($notifiable)
    {
        // Mengambil URL verifikasi bawaan
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email - LP3MT Kabupaten Kediri')
            ->view('emails.custom-verify', [
                'url' => $verificationUrl,
                'name' => $notifiable->name
            ]);
    }
}