<?php

namespace App\Models;

use Database\Factories\ApplicationDocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    /** @use HasFactory<ApplicationDocumentFactory> */
    use HasFactory;

    protected $fillable = ['application_id', 'document_type', 'original_name', 'path', 'mime_type', 'size'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
