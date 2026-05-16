<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingParticipant extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'meeting_id',
        'user_id',
        'role',
        'joined_at',
        'left_at',
        'duration_seconds',
        'is_present',
        'raised_hand',
        'can_share_screen',
        'can_record',
        'can_mute_others',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'duration_seconds' => 'integer',
        'is_present' => 'boolean',
        'raised_hand' => 'boolean',
        'can_share_screen' => 'boolean',
        'can_record' => 'boolean',
        'can_mute_others' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
