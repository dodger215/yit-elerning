<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'description',
        'meeting_type',
        'start_time',
        'end_time',
        'duration_minutes',
        'is_recurring',
        'recurrence_rule',
        'room_id',
        'meeting_url',
        'host_user_id',
        'max_participants',
        'waiting_room_enabled',
        'recording_enabled',
        'chat_enabled',
        'screen_sharing_enabled',
        'access_code',
        'recording_url',
        'transcript_url',
        'status',
        'course_id',
        'lesson_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
        'is_recurring' => 'boolean',
        'recurrence_rule' => 'array',
        'max_participants' => 'integer',
        'waiting_room_enabled' => 'boolean',
        'recording_enabled' => 'boolean',
        'chat_enabled' => 'boolean',
        'screen_sharing_enabled' => 'boolean',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(MeetingInvitation::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MeetingMessage::class);
    }

    public function polls(): HasMany
    {
        return $this->hasMany(MeetingPoll::class);
    }
}
