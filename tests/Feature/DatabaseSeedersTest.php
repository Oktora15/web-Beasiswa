<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Bookmark;
use App\Models\Criterion;
use App\Models\CriterionScholarship;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseSeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_twenty_scholarships_and_demo_accounts_idempotently(): void
    {
        Storage::fake('local');

        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('scholarships', 20);
        $this->assertDatabaseCount('users', 4);
        $this->assertDatabaseCount('profiles', 3);
        $this->assertDatabaseCount('criteria_scholarship', 40);
        $this->assertDatabaseCount('bookmarks', 3);
        $this->assertDatabaseCount('applications', 1);
        $this->assertDatabaseCount('application_documents', 1);

        $this->assertGreaterThanOrEqual(8, Scholarship::query()->distinct()->count('country'));
        $this->assertEqualsCanonicalizing(['S1', 'S2', 'S3'], Scholarship::query()->distinct()->orderBy('degree_level')->pluck('degree_level')->all());
        $this->assertGreaterThan(1, Scholarship::query()->distinct()->count('deadline'));
        $this->assertGreaterThan(0, Scholarship::where('language_test', 'none')->count());
        $this->assertGreaterThan(0, Scholarship::where('return_obligation', true)->count());
        $this->assertGreaterThan(0, Scholarship::where('return_obligation', false)->count());
        $this->assertSame(2, Scholarship::where('slug', 'lpdp-reguler-magister')->firstOrFail()->criteria()->count());
        $this->assertSame('Administrator Beasiswa', User::where('role', 'admin')->firstOrFail()->name);
        $this->assertSame(3, User::where('role', 'student')->count());
        $this->assertTrue(User::where('email', 'admin@beasiswahub.test')->firstOrFail()->isAdmin());
        $this->assertSame(4, Criterion::count());
        $this->assertInstanceOf(User::class, Application::firstOrFail()->user);
        $this->assertInstanceOf(Scholarship::class, Bookmark::firstOrFail()->scholarship);

        $document = ApplicationDocument::firstOrFail();
        $image = Storage::disk('local')->get($document->path);
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", substr($image, 0, 8));
        $this->assertTrue(Hash::check('AdminDemo!2026', User::where('email', 'admin@beasiswahub.test')->firstOrFail()->password));
    }

    public function test_criteria_scholarship_factory_creates_both_related_records(): void
    {
        $pivot = CriterionScholarship::factory()->create();

        $this->assertInstanceOf(Criterion::class, $pivot->criterion);
        $this->assertInstanceOf(Scholarship::class, $pivot->scholarship);
    }
}
