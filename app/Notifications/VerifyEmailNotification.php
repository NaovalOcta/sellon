<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     * Override parent to use Bahasa Indonesia template with Sellon branding.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email Akun SellOn Anda')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Terima kasih telah mendaftar di **SellOn** — marketplace mahasiswa UMM.')
            ->line('Klik tombol di bawah untuk memverifikasi alamat email Anda dan mengaktifkan akun.')
            ->action('Verifikasi Email Sekarang', $verificationUrl)
            ->line('Link verifikasi ini akan kadaluarsa dalam **60 menit**.')
            ->line('Jika Anda tidak merasa mendaftar di SellOn, abaikan email ini.')
            ->salutation('Salam, Tim SellOn');
    }
}
