<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nia',
        'nik_ktp',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'blood_type',
        'address',
        'phone_number',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relation',
        'occupation',
        'shirt_size',
        'ktp_photo_path',
        'profile_photo_path',
        'joined_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joined_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
