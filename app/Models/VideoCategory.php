<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'parent_category_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class, 'parent_category_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(VideoCategory::class, 'parent_category_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'category_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
