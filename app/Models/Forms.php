<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forms extends Model
{
    use HasFactory;

    protected $table = 'forms';

    protected $fillable = [
        'by',
        'title',
        'description',
        'fields',
        'form_type',
        'course_id',
        'cohort',
        'is_active',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'by');
    }

    public function formData(): HasMany
    {
        return $this->hasMany(FormData::class, 'form_id');
    }
}
