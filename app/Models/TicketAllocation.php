<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_ticket_id',
        'chapter_id',
        'allocated_quota',
        'distributed_quota',
    ];

    public function matchTicket(): BelongsTo
    {
        return $this->belongsTo(MatchTicket::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
