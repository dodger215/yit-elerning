<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'refresh_token',
        'access_token_jti',
        'device_name',
        'device_type',
        'browser_name',
        'os_name',
        'ip_address',
        'user_agent',
        'location_city',
        'location_country',
        'is_active',
        'last_activity_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_activity_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
