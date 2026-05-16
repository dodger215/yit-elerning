<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'parent_comment_id',
        'content',
        'like_count',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'like_count' => 'integer',
    ];

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(VideoComment::class, 'parent_comment_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(VideoComment::class, 'parent_comment_id');
    }
}
