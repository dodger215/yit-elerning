<?php

namespace App\Services;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\MeetingInvitationNotification;
use Illuminate\Support\Facades\Log;

class MeetingService
{
    /**
     * Create a new meeting and notify participants.
     */
    public function createMeeting(array $data, User $user): Meeting
    {
        $meeting = Meeting::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'meeting_type' => $data['meeting_type'],
            'start_time' => $data['start_time'],
            'end_time' => null,
            'room_id' => Str::slug($data['title']).'-'.Str::random(8),
            'host_user_id' => $user->id,
            'course_id' => $data['course_id'] ?? null,
            'status' => 'scheduled',
            'recording_enabled' => $data['recording_enabled'] ?? false,
        ]);

        // Role-based notifications for Board meetings
        if ($meeting->meeting_type === 'board' && ! empty($data['roles'])) {
            try {
                $this->notifyRoleMembers($meeting, $data['roles']);
            } catch (\Exception $e) {
                Log::error("Failed to notify members for meeting {$meeting->id}: " . $e->getMessage());
            }
        }

        return $meeting;
    }

    /**
     * Notify all users with selected roles about the meeting.
     */
    protected function notifyRoleMembers(Meeting $meeting, array $roleNames): void
    {
        $users = User::whereHas('roles', function ($query) use ($roleNames) {
            $query->whereIn('name', $roleNames);
        })->get();

        foreach ($users as $user) {
            try {
                $user->notify(new MeetingInvitationNotification($meeting));
            } catch (\Exception $e) {
                Log::error("Failed to notify user {$user->id} for meeting {$meeting->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Authorize a user to join a meeting.
     */
    public function authorizeJoin(Meeting $meeting, User $user): bool
    {
        // 1. Board Meetings: Only non-regular roles (at least one role that isn't 'regular')
        if ($meeting->meeting_type === 'board') {
            return $user->roles()->where('name', '!=', 'regular')->exists();
        }

        // 2. Instructor/Course Meetings: Instructor or Enrolled Students
        if ($meeting->meeting_type === 'course_live') {
            if ($user->id === $meeting->host_user_id) {
                return true;
            }

            if (! $meeting->course_id) {
                return false;
            }

            return $user->enrollments()
                ->where('course_id', $meeting->course_id)
                ->where('status', 'active')
                ->exists();
        }

        // 3. Public Meetings: Everyone authorized (guest logic handled in controller)
        if ($meeting->meeting_type === 'public') {
            return true;
        }

        return false;
    }

    /**
     * Determine if a user is the host of a meeting.
     */
    public function isHost(Meeting $meeting, User $user): bool
    {
        return $meeting->host_user_id === $user->id;
    }
}
