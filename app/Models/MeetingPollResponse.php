<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingPollResponse extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'poll_id',
        'user_id',
        'selected_option',
    ];

    protected $casts = [
        'selected_option' => 'integer',
        'responded_at' => 'datetime',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(MeetingPoll::class, 'poll_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
