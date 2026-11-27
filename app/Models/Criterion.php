<?php

namespace App\Models;

use Database\Factories\CriterionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Criterion extends Model
{
    /** @use HasFactory<CriterionFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function scholarships(): BelongsToMany
    {
        return $this->belongsToMany(Scholarship::class, 'criteria_scholarship')
            ->using(CriterionScholarship::class);
    }
}
