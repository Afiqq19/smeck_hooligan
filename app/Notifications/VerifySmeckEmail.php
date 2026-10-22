<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifySmeckEmail extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Akun Resmi SMeCK Hooligan — Salam Sada Roha!')
            ->greeting("Halo Saudaraku, {$notifiable->name}!")
            ->line('Terima kasih telah mendaftarkan diri sebagai anggota resmi SMeCK Hooligan (Suporter Medan Cinta Kinantan).')
            ->line('Untuk mengaktifkan akun Anda dan menerbitkan KTA Digital, silakan klik tombol verifikasi di bawah ini:')
            ->action('Verifikasi & Masuk ke Akun Saya', $verificationUrl)
            ->line('Link verifikasi ini hanya berlaku selama 60 menit demi keamanan akun suporter Anda.')
            ->line('Jika Anda tidak merasa mendaftar di Portal Resmi SMeCK Hooligan, abaikan pesan ini.')
            ->salutation("Salam Sada Roha!\nSMeCK Hooligan — Ayam Kinantan Selamanya 💚");
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
