<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_title',
        'match_time',
        'stadium',
        'type', // home, away
        'price',
        'total_quota',
        'remaining_quota',
        'away_destination',
        'bus_transport_info',
        'status', // open, closed, completed
    ];

    protected $casts = [
        'match_time' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function allocations(): HasMany
    {
        return $this->hasMany(TicketAllocation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(TicketOrder::class);
    }
}
