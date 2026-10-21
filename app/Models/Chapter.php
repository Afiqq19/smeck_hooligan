<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region_code',
        'leader_name',
        'basecamp_address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function voluntaryDues(): HasMany
    {
        return $this->hasMany(VoluntaryDue::class);
    }

    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function documentations(): HasMany
    {
        return $this->hasMany(ChapterDocumentation::class);
    }

    public function ticketAllocations(): HasMany
    {
        return $this->hasMany(TicketAllocation::class);
    }
}
