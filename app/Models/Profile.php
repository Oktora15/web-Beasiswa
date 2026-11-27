<?php

namespace App\Models;

use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /** @use HasFactory<ProfileFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'gpa', 'birth_date', 'ielts_score', 'toefl_score'];

    protected function casts(): array
    {
        return ['birth_date' => 'date', 'gpa' => 'decimal:2', 'ielts_score' => 'decimal:1'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
