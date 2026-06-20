<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormData extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'form_data';

    protected $fillable = [
        'id',
        'form_id',
        'user_id',
        'data',
        'email_to_notify',
        'grade',
        'feedback',
        'graded_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Forms::class, 'form_id');
    }
}
