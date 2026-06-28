<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public string $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        // Format the OTP to display with a dash: e.g. "123-456"
        $formattedOtp = substr($this->otp, 0, 3) . '-' . substr($this->otp, 3, 3);

        return (new MailMessage)
            ->subject('Verifikasi Email Akun SellOn Anda')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Terima kasih telah mendaftar di **SellOn** — marketplace mahasiswa UMM.')
            ->line('Gunakan kode OTP di bawah ini untuk memverifikasi alamat email Anda dan mengaktifkan akun:')
            ->line('## **' . $formattedOtp . '**')
            ->line('Masukkan kode OTP ini di halaman verifikasi SellOn.')
            ->line('Kode verifikasi ini akan kadaluarsa dalam **60 menit**.')
            ->line('Jika Anda tidak merasa mendaftar di SellOn, abaikan email ini.')
            ->salutation('Salam, Tim SellOn');
    }
}
