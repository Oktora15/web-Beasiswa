<?php

namespace Tests\Feature;

use App\Models\Scholarship;
use App\Models\User;
use App\Notifications\ScholarshipDeadlineReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarshipDeadlineReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_reminder_email_contains_the_deadline_and_escapes_scholarship_title(): void
    {
        $this->travelTo('2026-11-27 12:00:00');
        $student = User::factory()->create();
        $title = 'Beasiswa <script>alert("x")</script>';
        $scholarship = Scholarship::factory()->create([
            'title' => $title,
            'deadline' => today()->addDays(7),
        ]);

        $message = (new ScholarshipDeadlineReminder($scholarship, 7))->toMail($student);
        $content = (string) $message->render();

        $this->assertSame('Pengingat deadline beasiswa '.$title, $message->subject);
        $this->assertSame(route('scholarships.show', $scholarship), $message->actionUrl);
        $this->assertStringContainsString('7 hari', $content);
        $this->assertStringContainsString('&lt;script&gt;', $content);
        $this->assertStringNotContainsString('<script>alert', $content);
    }
}
