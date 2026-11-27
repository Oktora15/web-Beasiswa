<?php

namespace App\Models;

use Database\Factories\CriterionScholarshipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CriterionScholarship extends Pivot
{
    /** @use HasFactory<CriterionScholarshipFactory> */
    use HasFactory;

    protected $table = 'criteria_scholarship';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['criterion_id', 'scholarship_id'];

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class);
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }
}
