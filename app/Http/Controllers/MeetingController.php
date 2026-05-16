<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Services\MeetingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MeetingController extends Controller
{
    public function __construct(protected MeetingService $meetingService) {}

    /**
     * List all meetings for the user.
     */
    public function index()
    {
        $user = Auth::user();
        
        $meetings = Meeting::query()
            ->when(! $user->hasRole('supervisor'), function ($query) use ($user) {
                // For non-supervisors, show public meetings, their own hosted meetings, 
                // or meetings assigned to their specific roles.
                $query->where('meeting_type', 'public')
                    ->orWhere('host_user_id', $user->id)
                    ->orWhereHas('participants', fn($q) => $q->where('user_id', $user->id));
            })
            ->orderBy('start_time', 'asc')
            ->get();

        return Inertia::render('Meeting/Index', [
            'meetings' => $meetings,
        ]);
    }

    /**
     * Update an existing meeting.
     */
    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_type' => 'required|in:public,course_live,board',
            'start_time' => 'required|date',
        ]);

        // Restrict Board meetings to Supervisor only
        if ($validated['meeting_type'] === 'board' && ! auth()->user()->hasRole('supervisor')) {
            return back()->with('error', 'Only supervisors can manage board meetings.');
        }

        $meeting->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'meeting_type' => $validated['meeting_type'],
            'start_time' => $validated['start_time'],
            'end_time' => null,
        ]);

        return redirect()->route('meetings.index')->with('success', 'Meeting updated successfully!');
    }

    /**
     * Join a meeting room.
     */
    public function join(Request $request, string $roomId)
    {
        $meeting = Meeting::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        // Guest handling for Public meetings
        if (! $user) {
            if ($meeting->meeting_type !== 'public') {
                return redirect()->route('login')->with('error', 'Authentication required for this meeting.');
            }

            // For guests joining public meetings, we need a display name
            $guestName = $request->query('guest_name');
            if (! $guestName) {
                return Inertia::render('Meeting/GuestJoin', [
                    'meeting' => $meeting,
                ]);
            }

            return Inertia::render('Meeting/Room', [
                'meeting' => $meeting,
                'participantName' => $guestName,
                'isHost' => false,
                'isGuest' => true,
            ]);
        }

        // Authorize access for authenticated users
        if (! $this->meetingService->authorizeJoin($meeting, $user)) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to join this meeting.');
        }

        $isHost = $this->meetingService->isHost($meeting, $user);

        return Inertia::render('Meeting/Room', [
            'meeting' => $meeting,
            'participantName' => $user->username ?? $user->email,
            'isHost' => $isHost,
        ]);
    }

    /**
     * Create a new meeting.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_type' => 'required|in:public,course_live,board',
            'start_time' => 'required|date',
            'course_id' => 'nullable|exists:courses,id',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        // Restrict Board meetings to Supervisor only
        if ($validated['meeting_type'] === 'board' && ! auth()->user()->hasRole('supervisor')) {
            return back()->with('error', 'Only supervisors can schedule board meetings.');
        }

        $meeting = $this->meetingService->createMeeting($validated, auth()->user());

        return redirect()->route('dashboard')->with('success', 'Meeting scheduled successfully!');
    }
}
