<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'username',
        'first_name',
        'last_name',
        'avatar_url',
        'bio',
        'timezone',
        'last_active_at',
        'email_verified',
        'is_active',
        'oauth_provider',
        'oauth_provider_id',
        'oauth_access_token',
        'oauth_refresh_token',
        'oauth_token_expires_at',
        'phone_number',
        'phone_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'oauth_access_token',
        'oauth_refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_active_at' => 'datetime',
            'email_verified' => 'boolean',
            'is_active' => 'boolean',
            'oauth_token_expires_at' => 'datetime',
            'phone_verified' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('assigned_by', 'assigned_at');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    public function uploadedVideos(): HasMany
    {
        return $this->hasMany(Video::class, 'uploaded_by');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    public function hostedMeetings(): HasMany
    {
        return $this->hasMany(Meeting::class, 'host_user_id');
    }

    public function meetingParticipations(): HasMany
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(UserActivityLog::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        [$resource, $action] = explode('.', $permission);

        return $this->roles()->whereHas('permissions', function ($query) use ($resource, $action) {
            $query->where('resource', $resource)->where('action', $action);
        })->exists();
    }
}
