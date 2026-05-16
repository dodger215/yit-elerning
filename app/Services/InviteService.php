<?php

namespace App\Services;

use App\Models\MagicLink;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InviteService
{
    
    /**
     * Generate an invite link for a specific role.
     */
    public function generateInvite(string $email, string $roleName, User $invitedBy): string
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        $token = Str::uuid();

        // We can reuse MagicLink table or use a cache/dedicated table.
        // The user request mentions "invite auth like ClickUp/Jira".
        // Let's use the MagicLink model for the token storage.

        MagicLink::create([
            'email' => $email,
            'token' => $token,
            'purpose' => 'invite:'.$roleName,
            'expires_at' => Carbon::now()->addDays(7),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return route('auth.invite.verify', ['token' => $token]);
    }

    /**
     * Verify the invite and prepare the session for registration/login.
     */
    public function verifyInvite(string $token): ?array
    {
        $invite = MagicLink::where('token', $token)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $invite) {
            return null;
        }

        $roleName = str_replace('invite:', '', $invite->purpose);

        return [
            'email' => $invite->email,
            'role' => $roleName,
            'token' => $token,
        ];
    }
}
