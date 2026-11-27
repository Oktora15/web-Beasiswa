<?php

namespace Tests\Feature;

use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_toggle_a_bookmark_and_view_saved_scholarships(): void
    {
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create();

        $response = $this->actingAs($student)
            ->from(route('scholarships.index'))
            ->post(route('bookmarks.toggle', $scholarship));

        $response->assertRedirect(route('scholarships.index'));
        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $student->id,
            'scholarship_id' => $scholarship->id,
        ]);

        $this->get(route('scholarships.index'))
            ->assertOk()
            ->assertSee($scholarship->title)
            ->assertSee('Tersimpan');

        $this->get(route('bookmarks.index'))
            ->assertOk()
            ->assertSee('Beasiswa tersimpan')
            ->assertSee($scholarship->title)
            ->assertSee('Hapus');

        $response = $this->from(route('bookmarks.index'))
            ->post(route('bookmarks.toggle', $scholarship));

        $response->assertRedirect(route('bookmarks.index'));
        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $student->id,
            'scholarship_id' => $scholarship->id,
        ]);
    }

    public function test_scholarship_detail_shows_bookmark_state_for_student(): void
    {
        $student = User::factory()->create();
        $scholarship = Scholarship::factory()->create();
        $student->bookmarks()->create(['scholarship_id' => $scholarship->id]);

        $this->actingAs($student)
            ->get(route('scholarships.show', $scholarship))
            ->assertOk()
            ->assertSee('Hapus dari simpanan');
    }
}
