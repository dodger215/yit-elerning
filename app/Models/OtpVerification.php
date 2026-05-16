<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'email',
        'phone_number',
        'otp_code',
        'purpose',
        'is_used',
        'attempts',
        'expires_at',
        'delivery_method',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
