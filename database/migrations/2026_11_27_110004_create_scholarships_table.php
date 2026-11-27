<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('provider');
            $table->string('country', 100)->index();
            $table->enum('degree_level', ['S1', 'S2', 'S3'])->index();
            $table->enum('funding_type', ['fully', 'partial'])->index();
            $table->enum('language_test', ['none', 'ielts', 'toefl', 'ielts_or_toefl', 'waiver'])->default('none');
            $table->boolean('return_obligation')->default(false)->index();
            $table->unsignedTinyInteger('return_duration_years')->nullable();
            $table->decimal('min_gpa', 3, 2)->nullable();
            $table->unsignedTinyInteger('max_age')->nullable();
            $table->date('open_date')->nullable();
            $table->date('deadline')->index();
            $table->string('official_url', 2048);
            $table->text('description');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
