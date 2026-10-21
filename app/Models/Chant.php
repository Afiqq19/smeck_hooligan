<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chant extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'lyrics',
        'audio_path',
        'youtube_url',
        'duration',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
