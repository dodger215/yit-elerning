<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'short_description',
        'cover_image_url',
        'trailer_video_id',
        'price',
        'discount_price',
        'is_free',
        'currency',
        'level',
        'language',
        'duration_hours',
        'total_students',
        'total_reviews',
        'average_rating',
        'status',
        'instructor_id',
        'category_id',
        'published_at',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'total_students' => 'integer',
        'total_reviews' => 'integer',
        'duration_hours' => 'integer',
        'published_at' => 'datetime',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class);
    }

    public function trailer(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'trailer_video_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class)->orderBy('section_order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }
}
