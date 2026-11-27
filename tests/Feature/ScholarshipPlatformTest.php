<?php

namespace Tests\Feature;

use App\Mail\DeadlineReminder;
use App\Models\Application;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ScholarshipPlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_browse_and_filter_active_scholarships(): void
    {
        $matching = Scholarship::factory()->create([
            'country' => 'Jepang',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'is_active' => true,
        ]);
        $otherCountry = Scholarship::factory()->create(['country' => 'Australia', 'is_active' => true]);
        Scholarship::factory()->create(['country' => 'Jepang', 'is_active' => false]);

        Livewire::test('scholarship-filters')
            ->set('country', 'Jepang')
            ->set('degree_level', 'S2')
            ->set('funding_type', 'fully')
            ->assertSee($matching->title)
            ->assertDontSee($otherCountry->title);
    }

    public function test_student_can_register_and_only_student_role_is_assigned(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Mahasiswa Uji',
            'email' => 'uji@example.test',
            'password' => 'Password-123!',
            'password_confirmation' => 'Password-123!',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'uji@example.test', 'role' => 'student']);
    }

    public function test_student_can_submit_application_with_private_document(): void
    {
        Storage::fake('local');
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create(['deadline' => today()->addDays(5)]);

        $response = $this->actingAs($student)->post(route('applications.store', $scholarship), [
            'intent' => 'submit',
            'motivation' => str_repeat('Saya ingin belajar dan berkontribusi melalui program ini. ', 2),
            'documents' => [UploadedFile::fake()->createWithContent('transkrip.pdf', '%PDF-1.4 data dokumen')],
        ]);

        $response->assertRedirect(route('applications.index'));
        $this->assertDatabaseHas('applications', [
            'user_id' => $student->id,
            'scholarship_id' => $scholarship->id,
            'status' => 'submitted',
        ]);
        $application = Application::firstOrFail();
        $this->assertDatabaseHas('application_documents', ['application_id' => $application->id]);
        Storage::disk('local')->assertExists($application->documents()->firstOrFail()->path);
    }

    public function test_student_cannot_access_admin_panel(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_public_and_role_specific_pages_render(): void
    {
        $scholarship = Scholarship::factory()->create();
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
        $this->get(route('scholarships.index'))->assertOk();
        $this->get(route('scholarships.show', $scholarship))->assertOk();

        $student = User::factory()->create();
        $this->actingAs($student)->get(route('profile.edit'))->assertOk();
        $this->get(route('applications.index'))->assertOk();
        $this->get(route('bookmarks.index'))->assertOk();

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.scholarships.create'))->assertOk();
        $this->get(route('admin.scholarships.index'))->assertOk();
        $this->get(route('admin.applications.index'))->assertOk();
    }

    public function test_admin_can_create_a_scholarship(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.scholarships.store'), [
            'title' => 'Beasiswa Nusantara',
            'slug' => 'beasiswa-nusantara',
            'provider' => 'Yayasan Nusantara',
            'country' => 'Indonesia',
            'degree_level' => 'S1',
            'funding_type' => 'fully',
            'language_test' => 'none',
            'return_obligation' => false,
            'deadline' => today()->addMonth()->toDateString(),
            'official_url' => 'https://example.org/beasiswa',
            'description' => 'Program pendanaan pendidikan untuk mahasiswa Indonesia.',
            'is_active' => true,
        ])->assertRedirect(route('admin.scholarships.index'));

        $this->assertDatabaseHas('scholarships', ['slug' => 'beasiswa-nusantara']);
    }

    public function test_draft_can_be_completed_and_reminders_are_sent_once(): void
    {
        Storage::fake('local');
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create(['deadline' => today()->addDays(7)]);

        $this->actingAs($student)->post(route('applications.store', $scholarship), [
            'intent' => 'draft',
            'documents' => [UploadedFile::fake()->createWithContent('cv.pdf', '%PDF-1.4 cv')],
        ])->assertRedirect(route('applications.index'));

        $this->post(route('applications.store', $scholarship), [
            'intent' => 'submit',
            'motivation' => str_repeat('Saya berkomitmen untuk mengikuti seluruh proses program ini. ', 2),
        ])->assertRedirect(route('applications.index'));

        $this->assertDatabaseHas('applications', ['user_id' => $student->id, 'status' => 'submitted']);
        $this->assertDatabaseCount('applications', 1);

        $student->bookmarks()->create(['scholarship_id' => $scholarship->id]);
        $tomorrow = Scholarship::factory()->create(['deadline' => today()->addDay()]);
        $student->bookmarks()->create(['scholarship_id' => $tomorrow->id]);
        Mail::fake();
        $this->artisan('scholarships:send-deadline-reminders')->assertSuccessful();
        $this->artisan('scholarships:send-deadline-reminders')->assertSuccessful();

        Mail::assertSent(DeadlineReminder::class, 2);
        Mail::assertSent(DeadlineReminder::class, fn (DeadlineReminder $mail) => $mail->daysRemaining === 7);
        Mail::assertSent(DeadlineReminder::class, fn (DeadlineReminder $mail) => $mail->daysRemaining === 1);
        $this->assertNotNull($student->bookmarks()->firstOrFail()->refresh()->reminder_7_sent_at);
    }
}
