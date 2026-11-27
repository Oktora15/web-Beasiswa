<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ApplicationDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $application = Application::whereHas('user', fn ($query) => $query->where('email', config('seeding.students.1.email') ?: 'alya.maharani@beasiswahub.test'))->first();

        if ($application && ! $application->documents()->exists()) {
            $content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/r4sAAAAASUVORK5CYII=', true);
            $path = "applications/{$application->id}/contoh-dokumen.png";
            Storage::disk('local')->put($path, $content);

            ApplicationDocument::create([
                'application_id' => $application->id,
                'document_type' => 'Contoh dokumen',
                'original_name' => 'contoh-dokumen.png',
                'path' => $path,
                'mime_type' => 'image/png',
                'size' => strlen($content),
            ]);
        }
    }
}
