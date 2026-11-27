<?php

namespace Tests\Feature;

use App\ApplicationStatus;
use App\Models\Application;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_submitting_requires_cv_transcript_and_motivation_letter(): void
    {
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create();

        $response = $this->actingAs($student)
            ->from(route('scholarships.show', $scholarship))
            ->post(route('applications.store', $scholarship), ['intent' => 'submit']);

        $response->assertRedirect(route('scholarships.show', $scholarship))
            ->assertSessionHasErrors(['cv', 'transcript', 'motivation_letter']);
        $this->assertDatabaseCount('applications', 0);
    }

    public function test_application_upload_rejects_non_pdf_and_files_larger_than_two_megabytes(): void
    {
        Storage::fake('local');
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create();

        $response = $this->actingAs($student)
            ->from(route('scholarships.show', $scholarship))
            ->post(route('applications.store', $scholarship), [
                'intent' => 'submit',
                'cv' => UploadedFile::fake()->create('cv.pdf', 10, 'text/plain'),
                'transcript' => UploadedFile::fake()->create('transkrip.pdf', 2049, 'application/pdf'),
                'motivation_letter' => UploadedFile::fake()->createWithContent('surat-motivasi.pdf', '%PDF-1.4 surat'),
            ]);

        $response->assertRedirect(route('scholarships.show', $scholarship))
            ->assertSessionHasErrors(['cv', 'transcript']);
        $this->assertDatabaseCount('applications', 0);
        Storage::disk('local')->assertEmpty();
    }

    public function test_application_accepts_a_pdf_at_the_two_megabyte_limit(): void
    {
        Storage::fake('local');
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create();

        $response = $this->actingAs($student)
            ->post(route('applications.store', $scholarship), [
                'intent' => 'submit',
                'cv' => UploadedFile::fake()->create('cv.pdf', 2048, 'application/pdf'),
                'transcript' => UploadedFile::fake()->createWithContent('transkrip.pdf', '%PDF-1.4 transkrip'),
                'motivation_letter' => UploadedFile::fake()->createWithContent('surat-motivasi.pdf', '%PDF-1.4 surat'),
            ]);

        $response->assertRedirect(route('applications.index'));
        $this->assertDatabaseHas('application_documents', [
            'document_type' => 'cv',
            'size' => 2048 * 1024,
        ]);
    }

    public function test_student_cannot_set_application_owner_or_status_in_submission_payload(): void
    {
        Storage::fake('local');
        $student = User::factory()->create();
        $otherStudent = User::factory()->create();
        $scholarship = Scholarship::factory()->create();

        $response = $this->actingAs($student)->post(route('applications.store', $scholarship), [
            'intent' => 'submit',
            'motivation' => 'Saya ingin mengikuti program beasiswa ini.',
            'user_id' => $otherStudent->id,
            'status' => ApplicationStatus::Accepted->value,
            'cv' => UploadedFile::fake()->createWithContent('cv.pdf', '%PDF-1.4 CV'),
            'transcript' => UploadedFile::fake()->createWithContent('transkrip.pdf', '%PDF-1.4 transkrip'),
            'motivation_letter' => UploadedFile::fake()->createWithContent('surat-motivasi.pdf', '%PDF-1.4 surat'),
        ]);

        $response->assertRedirect(route('applications.index'));
        $this->assertDatabaseHas('applications', [
            'user_id' => $student->id,
            'scholarship_id' => $scholarship->id,
            'status' => ApplicationStatus::Submitted->value,
        ]);
        $this->assertDatabaseMissing('applications', [
            'user_id' => $otherStudent->id,
            'scholarship_id' => $scholarship->id,
        ]);
    }

    public function test_guest_is_redirected_to_login_for_application_list(): void
    {
        $this->get(route('applications.index'))
            ->assertRedirect(route('login'));
    }

    public function test_student_only_sees_and_changes_their_own_application(): void
    {
        $owner = User::factory()->create();
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create();
        $application = Application::factory()->create([
            'user_id' => $owner->id,
            'scholarship_id' => $scholarship->id,
            'status' => ApplicationStatus::Submitted,
        ]);

        $this->actingAs($owner)
            ->get(route('applications.show', $application))
            ->assertOk()
            ->assertSee('Terkirim');

        $this->actingAs($student)
            ->get(route('applications.index'))
            ->assertOk()
            ->assertDontSee($scholarship->title);

        $this->get(route('applications.show', $application))->assertForbidden();

        $this->actingAs($owner)
            ->post(route('applications.store', $scholarship), ['intent' => 'draft'])
            ->assertForbidden();

        $this->from(route('applications.index'))
            ->actingAs($student)
            ->post(route('applications.store', $scholarship), ['intent' => 'draft'])
            ->assertRedirect(route('applications.index'));

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'user_id' => $owner->id,
            'status' => ApplicationStatus::Submitted->value,
        ]);
        $this->assertDatabaseHas('applications', [
            'user_id' => $student->id,
            'scholarship_id' => $scholarship->id,
            'status' => ApplicationStatus::Draft->value,
        ]);
    }

    public function test_admin_must_follow_application_status_transitions(): void
    {
        $admin = User::factory()->admin()->create();
        $application = Application::factory()->create([
            'status' => ApplicationStatus::Submitted,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.applications.index'))
            ->patch(route('admin.applications.update', $application), [
                'status' => ApplicationStatus::Accepted->value,
            ])
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => ApplicationStatus::Submitted->value,
        ]);

        $this->from(route('admin.applications.index'))
            ->patch(route('admin.applications.update', $application), [
                'status' => ApplicationStatus::Review->value,
            ])
            ->assertRedirect(route('admin.applications.index'));

        $this->from(route('admin.applications.index'))
            ->patch(route('admin.applications.update', $application), [
                'status' => ApplicationStatus::Accepted->value,
            ])
            ->assertRedirect(route('admin.applications.index'));

        $application->refresh();
        $this->assertSame(ApplicationStatus::Accepted, $application->status);

        $this->from(route('admin.applications.index'))
            ->patch(route('admin.applications.update', $application), [
                'status' => ApplicationStatus::Rejected->value,
            ])
            ->assertSessionHasErrors('status');
    }

    public function test_admin_receives_a_localized_error_for_an_unsupported_application_status(): void
    {
        $admin = User::factory()->admin()->create();
        $application = Application::factory()->create([
            'status' => ApplicationStatus::Submitted,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.applications.index'))
            ->patch(route('admin.applications.update', $application), [
                'status' => 'archived',
            ]);

        $response->assertRedirect(route('admin.applications.index'))
            ->assertSessionHasErrors([
                'status' => 'Status lamaran yang dipilih tidak valid.',
            ]);
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => ApplicationStatus::Submitted->value,
        ]);
    }
}
