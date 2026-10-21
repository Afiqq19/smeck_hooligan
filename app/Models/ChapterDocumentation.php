<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterDocumentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'user_id',
        'title',
        'activity_type',
        'activity_date',
        'description',
        'photo_path',
        'is_highlighted',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'is_highlighted' => 'boolean',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
