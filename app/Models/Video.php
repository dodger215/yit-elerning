<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'duration',
        'file_size',
        'video_type',
        'resolution',
        'status',
        'view_count',
        'like_count',
        'comment_count',
        'share_count',
        'uploaded_by',
        'category_id',
        'music_track',
        'effect_filters',
        'chapters',
        'published_at',
    ];

    protected $casts = [
        'effect_filters' => 'array',
        'chapters' => 'array',
        'published_at' => 'datetime',
        'duration' => 'integer',
        'file_size' => 'integer',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'comment_count' => 'integer',
        'share_count' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class);
    }

    public function accessRules(): HasMany
    {
        return $this->hasMany(VideoAccessRule::class);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(VideoInteraction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(VideoComment::class);
    }

    public function watchProgresses(): HasMany
    {
        return $this->hasMany(VideoWatchProgress::class);
    }
}
