<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'email',
        'phone_number',
        'ip_address',
        'attempt_type',
        'is_successful',
        'failure_reason',
    ];

    protected $casts = [
        'is_successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];
}
