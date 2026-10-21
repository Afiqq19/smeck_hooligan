<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmeckPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'category',
        'address',
        'google_maps_url',
        'discount_rate',
        'contact_person',
        'phone_number',
        'logo_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
