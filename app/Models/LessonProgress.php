<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'enrollment_id',
        'lesson_id',
        'status',
        'score',
        'completed_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(CourseEnrollment::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class);
    }
}
