<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_ticket_id',
        'user_id',
        'chapter_id',
        'seat_number',
        'total_amount',
        'payment_status',
        'payment_proof_path',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function matchTicket(): BelongsTo
    {
        return $this->belongsTo(MatchTicket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
