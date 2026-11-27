<?php

namespace App\Notifications;

use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class ScholarshipDeadlineReminder extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Scholarship $scholarship, public int $daysRemaining) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        $deadline = $this->scholarship->deadline->translatedFormat('d F Y');

        return (new MailMessage)
            ->subject('Pengingat deadline beasiswa '.$this->scholarship->title)
            ->greeting('Halo '.$notifiable->name.',')
            ->line("Beasiswa {$this->scholarship->title} yang Anda simpan akan ditutup dalam {$this->daysRemaining} hari, pada {$deadline}.")
            ->action('Lihat detail dan siapkan pendaftaran', route('scholarships.show', $this->scholarship))
            ->line('Pastikan dokumen pendaftaran Anda sudah lengkap sebelum deadline.');
    }
}
