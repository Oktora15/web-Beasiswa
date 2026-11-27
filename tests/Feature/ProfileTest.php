<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_save_academic_profile_and_country_of_origin(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'gpa' => '3.75',
                'birth_date' => '2002-04-15',
                'country_of_origin' => 'Indonesia',
                'ielts_score' => '7.5',
                'toefl_score' => '105',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseHas('profiles', ['user_id' => $student->id]);

        $profile = $student->profile()->firstOrFail();
        $this->assertSame('3.75', $profile->gpa);
        $this->assertSame('2002-04-15', $profile->birth_date->toDateString());
        $this->assertSame('Indonesia', $profile->country_of_origin);
        $this->assertSame('7.5', $profile->ielts_score);
        $this->assertSame(105, $profile->toefl_score);
    }

    public function test_profile_rejects_scores_outside_supported_ranges(): void
    {
        $student = User::factory()->create();

        $response = $this->actingAs($student)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'gpa' => '4.1',
                'birth_date' => today()->toDateString(),
                'country_of_origin' => str_repeat('A', 101),
                'ielts_score' => '7.3',
                'toefl_score' => '121',
            ]);

        $response->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors([
                'gpa' => 'IPK harus berada di antara 0 dan 4.',
                'birth_date' => 'Tanggal lahir harus sebelum hari ini.',
                'country_of_origin' => 'Negara asal maksimal 100 karakter.',
                'ielts_score' => 'Skor IELTS harus menggunakan kelipatan 0,5.',
                'toefl_score' => 'Skor TOEFL harus berada di antara 0 dan 120.',
            ]);
        $this->assertDatabaseMissing('profiles', ['user_id' => $student->id]);
    }

    public function test_scholarship_detail_marks_a_matching_profile_as_eligible(): void
    {
        $this->travelTo('2026-11-27 12:00:00');
        $student = User::factory()->create();
        Profile::factory()->for($student)->create([
            'gpa' => 3.5,
            'birth_date' => '2000-05-01',
        ]);
        $scholarship = Scholarship::factory()->create([
            'min_gpa' => 3.0,
            'max_age' => 30,
        ]);

        $response = $this->actingAs($student)->get(route('scholarships.show', $scholarship));

        $response->assertOk()
            ->assertSee('Kecocokan profil Anda')
            ->assertSeeText('Memenuhi')
            ->assertDontSeeText('Belum memenuhi')
            ->assertDontSeeText('Lengkapi profil');
    }

    public function test_scholarship_detail_marks_gpa_and_age_requirements_not_met(): void
    {
        $this->travelTo('2026-11-27 12:00:00');
        $student = User::factory()->create();
        Profile::factory()->for($student)->create([
            'gpa' => 3.0,
            'birth_date' => '1990-05-01',
        ]);
        $scholarship = Scholarship::factory()->create([
            'min_gpa' => 3.5,
            'max_age' => 30,
        ]);

        $response = $this->actingAs($student)->get(route('scholarships.show', $scholarship));

        $response->assertOk()
            ->assertSee('Belum memenuhi')
            ->assertDontSee('Lengkapi profil untuk hasil lebih akurat');
    }

    public function test_scholarship_detail_requests_missing_profile_data_for_eligibility(): void
    {
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create([
            'min_gpa' => 3.5,
            'max_age' => 30,
        ]);

        $response = $this->actingAs($student)->get(route('scholarships.show', $scholarship));

        $response->assertOk()
            ->assertSee('Lengkapi profil')
            ->assertSee(route('profile.edit'));
    }
}
