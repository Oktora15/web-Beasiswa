<?php

namespace Tests\Feature;

use App\ApplicationStatus;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Criterion;
use App\Models\Scholarship;
use App\Models\User;
use App\Notifications\ScholarshipDeadlineReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
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

    public function test_public_filters_scholarships_by_country_degree_funding_language_return_gpa_and_age(): void
    {
        $matching = Scholarship::factory()->create([
            'country' => 'Jepang',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'language_test' => 'none',
            'return_obligation' => false,
            'min_gpa' => 3.2,
            'max_age' => 30,
        ]);
        $waiver = Scholarship::factory()->create([
            'country' => 'Jepang',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'language_test' => 'waiver',
            'return_obligation' => false,
            'min_gpa' => 3.0,
            'max_age' => 28,
        ]);
        $requiringTest = Scholarship::factory()->create([
            'country' => 'Jepang',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'language_test' => 'ielts',
            'return_obligation' => false,
            'min_gpa' => 3.0,
            'max_age' => 28,
        ]);
        $wrongCountry = Scholarship::factory()->create([
            'country' => 'Australia',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'language_test' => 'none',
            'return_obligation' => false,
        ]);
        Scholarship::factory()->create([
            'country' => 'Jepang',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'language_test' => 'none',
            'return_obligation' => true,
        ]);
        Scholarship::factory()->create([
            'country' => 'Jepang',
            'degree_level' => 'S1',
            'funding_type' => 'partial',
            'language_test' => 'none',
            'return_obligation' => false,
        ]);
        Scholarship::factory()->create([
            'country' => 'Jepang',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'language_test' => 'none',
            'return_obligation' => false,
            'min_gpa' => 3.8,
        ]);
        Scholarship::factory()->create([
            'country' => 'Jepang',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'language_test' => 'none',
            'return_obligation' => false,
            'max_age' => 22,
        ]);

        $response = $this->get(route('scholarships.index', [
            'country' => 'Jepang',
            'degree_level' => 'S2',
            'funding_type' => 'fully',
            'no_language_test' => '1',
            'return_obligation' => '0',
            'min_gpa' => '3.5',
            'max_age' => '25',
        ]));

        $response->assertOk()
            ->assertSee($matching->title)
            ->assertDontSee($waiver->title)
            ->assertDontSee($requiringTest->title)
            ->assertDontSee($wrongCountry->title)
            ->assertSee('Tanpa IELTS')
            ->assertSee('Fully Funded')
            ->assertSee('Tidak Wajib Pulang');
    }

    public function test_open_filter_excludes_closed_and_not_yet_open_scholarships_and_orders_deadlines(): void
    {
        $later = Scholarship::factory()->create(['deadline' => today()->addDays(10)]);
        $soonest = Scholarship::factory()->create(['deadline' => today()->addDays(2)]);
        Scholarship::factory()->create(['deadline' => today()->subDay()]);
        Scholarship::factory()->create([
            'open_date' => today()->addDay(),
            'deadline' => today()->addDays(10),
        ]);
        Scholarship::factory()->create([
            'is_active' => false,
            'deadline' => today()->addDay(),
        ]);

        $response = $this->get(route('scholarships.index', ['open' => '1']));

        $response->assertOk();
        $scholarships = $response->viewData('scholarships')->getCollection();
        $this->assertSame([$soonest->id, $later->id], $scholarships->pluck('id')->all());
    }

    public function test_pagination_keeps_the_active_filters_in_its_links(): void
    {
        Scholarship::factory()->count(10)->create(['country' => 'Jepang']);

        $response = $this->get(route('scholarships.index', ['country' => 'Jepang']));

        $response->assertOk();
        $secondPageUrl = $response->viewData('scholarships')->url(2);
        $this->assertStringContainsString('country=Jepang', $secondPageUrl);
        $this->assertStringContainsString('page=2', $secondPageUrl);
    }

    public function test_public_filter_rejects_an_unsupported_degree_level(): void
    {
        $response = $this->from(route('scholarships.index'))
            ->get(route('scholarships.index', ['degree_level' => 'PhD']));

        $response->assertRedirect(route('scholarships.index'))
            ->assertSessionHasErrors('degree_level');
    }

    public function test_public_filter_rejects_invalid_gpa_age_and_open_values(): void
    {
        $response = $this->from(route('scholarships.index'))
            ->get(route('scholarships.index', [
                'min_gpa' => '4.1',
                'max_age' => '15',
                'open' => 'not-a-boolean',
            ]));

        $response->assertRedirect(route('scholarships.index'))
            ->assertSessionHasErrors([
                'min_gpa' => 'IPK harus berada di antara 0 dan 4.',
                'max_age' => 'Usia harus berada di antara 16 dan 100 tahun.',
                'open' => 'Pilihan status pembukaan tidak valid.',
            ]);
    }

    public function test_student_can_register_and_only_student_role_is_assigned(): void
    {
        $response = $this->post(route('register'), [
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
            'cv' => UploadedFile::fake()->createWithContent('cv.pdf', '%PDF-1.4 CV'),
            'transcript' => UploadedFile::fake()->createWithContent('transkrip.pdf', '%PDF-1.4 transkrip'),
            'motivation_letter' => UploadedFile::fake()->createWithContent('surat-motivasi.pdf', '%PDF-1.4 surat'),
            'language_certificate' => UploadedFile::fake()->createWithContent('sertifikat.pdf', '%PDF-1.4 sertifikat'),
        ]);

        $response->assertRedirect(route('applications.index'));
        $this->assertDatabaseHas('applications', [
            'user_id' => $student->id,
            'scholarship_id' => $scholarship->id,
            'status' => 'submitted',
        ]);
        $application = Application::firstOrFail();
        $this->assertDatabaseCount('application_documents', 4);

        foreach (['cv', 'transcript', 'motivation_letter', 'language_certificate'] as $documentType) {
            $document = $application->documents()->where('document_type', $documentType)->firstOrFail();
            Storage::disk('local')->assertExists($document->path);
            Storage::disk('public')->assertMissing($document->path);
        }
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
        $criterion = Criterion::factory()->create();

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
            'criteria' => [$criterion->id],
        ])->assertRedirect(route('admin.scholarships.index'));

        $this->assertDatabaseHas('scholarships', ['slug' => 'beasiswa-nusantara']);
        $this->assertDatabaseHas('criteria_scholarship', [
            'criterion_id' => $criterion->id,
            'scholarship_id' => Scholarship::where('slug', 'beasiswa-nusantara')->value('id'),
        ]);
    }

    public function test_admin_scholarship_validation_errors_are_localized(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->from(route('admin.scholarships.create'))
            ->post(route('admin.scholarships.store'), []);

        $response->assertRedirect(route('admin.scholarships.create'))
            ->assertSessionHasErrors([
                'title' => 'Nama beasiswa wajib diisi.',
                'slug' => 'Slug URL wajib diisi.',
                'deadline' => 'Deadline beasiswa wajib diisi.',
            ]);
    }

    public function test_admin_can_replace_a_scholarships_additional_criteria(): void
    {
        $admin = User::factory()->admin()->create();
        $existingCriterion = Criterion::factory()->create();
        $newCriterion = Criterion::factory()->create();
        $scholarship = Scholarship::factory()->create();
        $scholarship->criteria()->attach($existingCriterion);

        $this->actingAs($admin)->put(route('admin.scholarships.update', $scholarship), [
            'title' => $scholarship->title,
            'slug' => $scholarship->slug,
            'provider' => $scholarship->provider,
            'country' => $scholarship->country,
            'degree_level' => $scholarship->degree_level,
            'funding_type' => $scholarship->funding_type,
            'language_test' => $scholarship->language_test,
            'return_obligation' => $scholarship->return_obligation,
            'return_duration_years' => $scholarship->return_duration_years,
            'min_gpa' => $scholarship->min_gpa,
            'max_age' => $scholarship->max_age,
            'open_date' => $scholarship->open_date->toDateString(),
            'deadline' => $scholarship->deadline->toDateString(),
            'official_url' => $scholarship->official_url,
            'description' => $scholarship->description,
            'is_active' => $scholarship->is_active,
            'criteria' => [$newCriterion->id],
        ])->assertRedirect(route('admin.scholarships.index'));

        $this->assertDatabaseMissing('criteria_scholarship', [
            'criterion_id' => $existingCriterion->id,
            'scholarship_id' => $scholarship->id,
        ]);
        $this->assertDatabaseHas('criteria_scholarship', [
            'criterion_id' => $newCriterion->id,
            'scholarship_id' => $scholarship->id,
        ]);
    }

    public function test_admin_can_filter_applications_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        $submitted = Application::factory()->create(['status' => ApplicationStatus::Submitted]);
        Application::factory()->create(['status' => ApplicationStatus::Accepted]);

        $response = $this->actingAs($admin)->get(route('admin.applications.index', [
            'status' => ApplicationStatus::Submitted->value,
        ]));

        $response->assertOk()
            ->assertViewHas('applications', fn ($applications): bool => $applications->total() === 1
                && $applications->first()->is($submitted))
            ->assertSee($submitted->user->email);
    }

    public function test_admin_can_review_application_and_see_private_document_link(): void
    {
        $admin = User::factory()->admin()->create();
        $application = Application::factory()->create(['status' => ApplicationStatus::Submitted]);
        ApplicationDocument::factory()->create([
            'application_id' => $application->id,
            'document_type' => 'cv',
            'original_name' => 'cv-mahasiswa.pdf',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.applications.show', $application))
            ->assertOk()
            ->assertSee('cv-mahasiswa.pdf')
            ->assertSee('Unduh dokumen')
            ->assertSee('Simpan status');
    }

    public function test_admin_dashboard_summarizes_application_statuses_and_popular_scholarships(): void
    {
        $admin = User::factory()->admin()->create();
        $popularScholarship = Scholarship::factory()->create(['title' => 'Beasiswa Paling Diminati']);
        $otherScholarship = Scholarship::factory()->create(['title' => 'Beasiswa Lain']);
        Application::factory()->count(2)->create([
            'scholarship_id' => $popularScholarship->id,
            'status' => ApplicationStatus::Submitted,
        ]);
        Application::factory()->create([
            'scholarship_id' => $otherScholarship->id,
            'status' => ApplicationStatus::Review,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('applicationStatuses', fn (array $statuses): bool => $statuses[1]['count'] === 2
                && $statuses[2]['count'] === 1)
            ->assertViewHas('mostAppliedScholarships', fn ($scholarships): bool => $scholarships->first()->is($popularScholarship))
            ->assertSee('Lamaran menurut status')
            ->assertSee('Beasiswa paling banyak dilamar')
            ->assertSee('Beasiswa Paling Diminati');
    }

    public function test_draft_can_be_completed_and_reminders_are_sent_once(): void
    {
        $this->travelTo('2026-11-27 08:00:00');
        Storage::fake('local');
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create(['deadline' => today()->addDays(7)]);

        $this->actingAs($student)->post(route('applications.store', $scholarship), [
            'intent' => 'draft',
            'cv' => UploadedFile::fake()->createWithContent('cv.pdf', '%PDF-1.4 cv'),
        ])->assertRedirect(route('applications.index'));

        $this->post(route('applications.store', $scholarship), [
            'intent' => 'submit',
            'motivation' => str_repeat('Saya berkomitmen untuk mengikuti seluruh proses program ini. ', 2),
            'transcript' => UploadedFile::fake()->createWithContent('transkrip.pdf', '%PDF-1.4 transkrip'),
            'motivation_letter' => UploadedFile::fake()->createWithContent('surat-motivasi.pdf', '%PDF-1.4 surat'),
        ])->assertRedirect(route('applications.index'));

        $this->assertDatabaseHas('applications', ['user_id' => $student->id, 'status' => 'submitted']);
        $this->assertDatabaseCount('applications', 1);

        $student->bookmarks()->create(['scholarship_id' => $scholarship->id]);
        $tomorrow = Scholarship::factory()->create(['deadline' => today()->addDay()]);
        $student->bookmarks()->create(['scholarship_id' => $tomorrow->id]);
        $outsideReminderWindow = Scholarship::factory()->create(['deadline' => today()->addDays(3)]);
        $student->bookmarks()->create(['scholarship_id' => $outsideReminderWindow->id]);
        $inactiveScholarship = Scholarship::factory()->create([
            'deadline' => today()->addDays(7),
            'is_active' => false,
        ]);
        $student->bookmarks()->create(['scholarship_id' => $inactiveScholarship->id]);

        Notification::fake();
        $this->artisan('scholarships:send-deadline-reminders')->assertSuccessful();
        $this->artisan('scholarships:send-deadline-reminders')->assertSuccessful();

        Notification::assertSentTimes(ScholarshipDeadlineReminder::class, 2);
        Notification::assertSentTo(
            $student,
            ScholarshipDeadlineReminder::class,
            fn (ScholarshipDeadlineReminder $reminder, array $channels): bool => $reminder->daysRemaining === 7
                && $reminder->scholarship->is($scholarship)
                && $channels === ['mail'],
        );
        Notification::assertSentTo(
            $student,
            ScholarshipDeadlineReminder::class,
            fn (ScholarshipDeadlineReminder $reminder, array $channels): bool => $reminder->daysRemaining === 1
                && $reminder->scholarship->is($tomorrow)
                && $channels === ['mail'],
        );
        $this->assertNotNull($student->bookmarks()->firstOrFail()->refresh()->reminder_7_sent_at);
        $this->assertNotNull($student->bookmarks()->where('scholarship_id', $tomorrow->id)->firstOrFail()->refresh()->reminder_1_sent_at);
        $this->assertNull($student->bookmarks()->where('scholarship_id', $outsideReminderWindow->id)->firstOrFail()->reminder_7_sent_at);
        $this->assertNull($student->bookmarks()->where('scholarship_id', $inactiveScholarship->id)->firstOrFail()->reminder_7_sent_at);
    }
}
