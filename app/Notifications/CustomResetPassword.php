<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class CustomResetPassword extends ResetPasswordNotification
{
    use Queueable;

    public function toMail($notifiable)
    {
        // Mengambil URL reset password bawaan Laravel
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Mengarahkan ke file Blade custom kita
        return (new MailMessage)
            ->subject('Pengaturan Ulang Sandi - LP3MT Kabupaten Kediri')
            ->view('emails.custom-reset', [
                'url' => $url,
                'email' => $notifiable->getEmailForPasswordReset()
            ]);
    }
}