<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingInvitation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'meeting_id',
        'invited_user_id',
        'invited_by',
        'email',
        'invitation_token',
        'status',
        'response_at',
        'expires_at',
    ];

    protected $casts = [
        'response_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function invitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
