<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLesson extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'section_id',
        'video_id',
        'title',
        'description',
        'content',
        'lesson_type',
        'lesson_order',
        'duration_minutes',
        'is_preview',
        'quiz_data',
        'assignment_data',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'quiz_data' => 'array',
        'assignment_data' => 'array',
        'lesson_order' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class, 'lesson_id');
    }
}
