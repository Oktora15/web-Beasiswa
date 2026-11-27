<?php

namespace App\Console\Commands;

use App\Mail\DeadlineReminder;
use App\Models\Bookmark;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendScholarshipDeadlineReminders extends Command
{
    protected $signature = 'scholarships:send-deadline-reminders';

    protected $description = 'Kirim pengingat deadline beasiswa H-7 dan H-1 kepada pengguna yang menyimpan beasiswa.';

    public function handle(): int
    {
        foreach ([7 => 'reminder_7_sent_at', 1 => 'reminder_1_sent_at'] as $days => $sentColumn) {
            Bookmark::query()
                ->whereNull($sentColumn)
                ->whereHas('scholarship', fn ($query) => $query->published()->whereDate('deadline', today()->addDays($days)))
                ->with(['user', 'scholarship'])
                ->each(function (Bookmark $bookmark) use ($days, $sentColumn): void {
                    Mail::to($bookmark->user)->send(new DeadlineReminder($bookmark->scholarship, $bookmark->user, $days));
                    $bookmark->update([$sentColumn => now()]);
                });
        }

        $this->info('Pengingat deadline berhasil diproses.');

        return self::SUCCESS;
    }
}
