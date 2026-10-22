<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueStanding extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_name',
        'group_name',
        'position',
        'team_name',
        'team_code',
        'team_logo',
        'played',
        'won',
        'drawn',
        'lost',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
        'form',
        'zone_status',
        'is_our_team',
        'last_synced_at',
    ];

    protected $casts = [
        'position' => 'integer',
        'played' => 'integer',
        'won' => 'integer',
        'drawn' => 'integer',
        'lost' => 'integer',
        'goals_for' => 'integer',
        'goals_against' => 'integer',
        'goal_difference' => 'integer',
        'points' => 'integer',
        'is_our_team' => 'boolean',
        'last_synced_at' => 'datetime',
    ];
}
