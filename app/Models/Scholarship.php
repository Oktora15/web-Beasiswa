<?php

namespace App\Models;

use Database\Factories\ScholarshipFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholarship extends Model
{
    /** @use HasFactory<ScholarshipFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'provider', 'country', 'degree_level', 'funding_type',
        'language_test', 'return_obligation', 'return_duration_years', 'min_gpa',
        'max_age', 'open_date', 'deadline', 'official_url', 'description', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'return_obligation' => 'boolean',
            'is_active' => 'boolean',
            'open_date' => 'date',
            'deadline' => 'date',
            'min_gpa' => 'decimal:2',
        ];
    }

    /**
     * Apply the supported public scholarship filters and deadline ordering.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['country'] ?? null, fn ($query, $country) => $query->where('country', $country))
            ->when($filters['degree_level'] ?? null, fn ($query, $degree) => $query->where('degree_level', $degree))
            ->when($filters['funding_type'] ?? null, fn ($query, $funding) => $query->where('funding_type', $funding))
            ->when($filters['language_test'] ?? null, fn ($query, $test) => $query->where('language_test', $test))
            ->when(
                filter_var($filters['no_language_test'] ?? false, FILTER_VALIDATE_BOOLEAN),
                fn ($query) => $query->where('language_test', 'none'),
            )
            ->when(
                isset($filters['return_obligation']) && $filters['return_obligation'] !== '',
                fn ($query) => $query->where(
                    'return_obligation',
                    filter_var($filters['return_obligation'], FILTER_VALIDATE_BOOLEAN),
                ),
            )
            ->when(isset($filters['min_gpa']) && $filters['min_gpa'] !== '', fn ($query) => $query->where(fn ($query) => $query->whereNull('min_gpa')->orWhere('min_gpa', '<=', $filters['min_gpa'])))
            ->when(isset($filters['max_age']) && $filters['max_age'] !== '', fn ($query) => $query->where(fn ($query) => $query->whereNull('max_age')->orWhere('max_age', '>=', $filters['max_age'])))
            ->when(filter_var($filters['open'] ?? false, FILTER_VALIDATE_BOOLEAN), fn ($query) => $query->currentlyOpen())
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where(fn ($query) => $query->where('title', 'like', "%{$search}%")->orWhere('provider', 'like', "%{$search}%")->orWhere('country', 'like', "%{$search}%")))
            ->when(($filters['sort'] ?? 'deadline_asc') === 'deadline_desc', fn ($query) => $query->orderByDesc('deadline'), fn ($query) => $query->orderBy('deadline'));
    }

    public function criteria(): BelongsToMany
    {
        return $this->belongsToMany(Criterion::class, 'criteria_scholarship')
            ->using(CriterionScholarship::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentlyOpen(Builder $query): Builder
    {
        return $query
            ->where(fn ($query) => $query->whereNull('open_date')->orWhereDate('open_date', '<=', today()))
            ->whereDate('deadline', '>=', today());
    }
}
