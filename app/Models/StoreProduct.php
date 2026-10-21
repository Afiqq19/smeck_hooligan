<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'member_price',
        'stock',
        'description',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'member_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
