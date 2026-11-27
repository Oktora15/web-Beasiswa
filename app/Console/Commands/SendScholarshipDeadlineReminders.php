<?php

namespace App\Console\Commands;

use App\Models\Bookmark;
use App\Notifications\ScholarshipDeadlineReminder;
use Illuminate\Console\Command;

class SendScholarshipDeadlineReminders extends Command
{
    protected $signature = 'scholarships:send-deadline-reminders';

    protected $description = 'Kirim pengingat deadline beasiswa H-7 dan H-1 kepada pengguna yang menyimpan beasiswa.';

    public function handle(): int
    {
        foreach ([7 => 'reminder_7_sent_at', 1 => 'reminder_1_sent_at'] as $days => $sentColumn) {
            Bookmark::query()
                ->whereNull($sentColumn)
                ->whereHas('user', fn ($query) => $query->where('role', 'student'))
                ->whereHas('scholarship', fn ($query) => $query->published()->whereDate('deadline', today()->addDays($days)))
                ->with(['user', 'scholarship'])
                ->chunkById(100, function ($bookmarks) use ($days, $sentColumn): void {
                    foreach ($bookmarks as $bookmark) {
                        $bookmark->user->notify(new ScholarshipDeadlineReminder($bookmark->scholarship, $days));
                        $bookmark->update([$sentColumn => now()]);
                    }
                });
        }

        $this->info('Pengingat deadline berhasil diproses.');

        return self::SUCCESS;
    }
}
