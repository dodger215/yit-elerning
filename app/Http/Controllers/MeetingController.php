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
                    ->orWhereHas('participants', fn ($q) => $q->where('user_id', $user->id));
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
        // Enforce privilege: only host or supervisor can edit
        if ($meeting->host_user_id !== auth()->id() && ! auth()->user()->hasRole('supervisor')) {
            return back()->with('error', 'You are not authorized to edit this meeting.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_type' => 'required|in:public,course_live,board',
            'start_time' => 'required|date',
            'recording_enabled' => 'boolean',
            'guest_emails' => 'nullable|string',
        ]);

        // Restrict Board meetings to Supervisor only
        if ($validated['meeting_type'] === 'board' && ! auth()->user()->hasRole('supervisor')) {
            return back()->with('error', 'Only supervisors can manage board meetings.');
        }

        $emails = null;
        if (!empty($validated['guest_emails']) && $validated['meeting_type'] === 'public') {
            $emails = array_filter(array_map('trim', explode(',', $validated['guest_emails'])));
        }

        $meeting->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'meeting_type' => $validated['meeting_type'],
            'start_time' => $validated['start_time'],
            'end_time' => null,
            'recording_enabled' => $validated['recording_enabled'] ?? false,
            'guest_emails' => $emails,
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
            'recording_enabled' => 'boolean',
            'guest_emails' => 'nullable|string',
        ]);

        // Restrict Board meetings to Supervisor only
        if ($validated['meeting_type'] === 'board' && ! auth()->user()->hasRole('supervisor')) {
            return back()->with('error', 'Only supervisors can schedule board meetings.');
        }

        $meeting = $this->meetingService->createMeeting($validated, auth()->user());

        if (!empty($validated['guest_emails']) && $validated['meeting_type'] === 'public') {
            $emails = array_filter(array_map('trim', explode(',', $validated['guest_emails'])));
            $meeting->update(['guest_emails' => $emails]);

            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\MeetingGuestInvite($meeting));
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Meeting scheduled successfully!');
    }

    /**
     * Upload a meeting recording.
     */
    public function uploadRecording(Request $request, Meeting $meeting)
    {
        $request->validate([
            'recording' => 'required|file',
            'chunk_index' => 'nullable|integer',
            'total_chunks' => 'nullable|integer',
            'file_type' => 'nullable|string|in:video,audio',
        ]);

        $file = $request->file('recording');
        
        if (!$file->isValid()) {
            return response()->json(['success' => false, 'message' => $file->getErrorMessage()], 422);
        }

        $chunkIndex = $request->input('chunk_index', 0);
        $totalChunks = $request->input('total_chunks', 1);
        $fileType = $request->input('file_type', 'video');
        $extension = $fileType === 'audio' ? 'webm' : 'mp4';
        $fileName = 'meeting_'.$meeting->id.'_recording.'.$extension;
        
        $path = storage_path('app/public/recordings');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        $finalPath = $path . '/' . $fileName;

        if ($chunkIndex == 0 && file_exists($finalPath)) {
            @unlink($finalPath);
        }

        $chunk = file_get_contents($file->getRealPath());
        file_put_contents($finalPath, $chunk, FILE_APPEND);

        if ($chunkIndex == $totalChunks - 1) {
            if ($fileType === 'video') {
                $meeting->update([
                    'recording_url' => route('meetings.download-recording', ['file' => $fileName]),
                ]);
            }
            return response()->json(['success' => true, 'completed' => true]);
        }

        return response()->json(['success' => true, 'completed' => false]);
    }

    /**
     * Display recorded meetings.
     */
    public function recordings()
    {
        $meetings = Meeting::whereNotNull('recording_url')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Meeting/Recordings', [
            'meetings' => $meetings,
        ]);
    }

    /**
     * Stream or download a meeting recording.
     */
    public function downloadRecording($file)
    {
        $path = storage_path('app/public/recordings/'.$file);

        if (! file_exists($path)) {
            $path = storage_path('app/public/meeting_recordings/'.$file);
        }

        if (! file_exists($path)) {
            abort(404, 'Recording not found.');
        }

        if (request()->has('download')) {
            return response()->download($path, 'Meeting-Recording-'.explode('.', $file)[0].'.mp4', [
                'Content-Type' => 'video/mp4',
            ]);
        }

        return response()->file($path);
    }

    /**
     * Show the meeting ended/goodbye page.
     */
    public function ended(Request $request, string $roomId)
    {
        $meeting = Meeting::where('room_id', $roomId)->firstOrFail();
        $isGuest = ! auth()->check();

        // Authenticated users go back to meetings list; guests go to homepage
        $returnUrl = $isGuest
            ? url('/')
            : route('meetings.index');

        return Inertia::render('Meeting/Ended', [
            'meeting' => $meeting,
            'returnUrl' => $returnUrl,
            'isGuest' => $isGuest,
        ]);
    }

    /**
     * Delete a meeting.
     */
    public function destroy(Meeting $meeting)
    {
        // Enforce privilege: only host or supervisor can delete
        if ($meeting->host_user_id !== auth()->id() && ! auth()->user()->hasRole('supervisor')) {
            return back()->with('error', 'You are not authorized to delete this meeting.');
        }

        $meeting->delete();

        return redirect()->route('meetings.index')->with('success', 'Meeting deleted successfully.');
    }

    /**
     * Resend invites for public meetings.
     */
    public function resendInvites(Request $request, Meeting $meeting)
    {
        if ($meeting->meeting_type !== 'public' || empty($meeting->guest_emails)) {
            return back()->with('error', 'No invites to send.');
        }
        
        if ($meeting->host_user_id !== auth()->id() && ! auth()->user()->hasRole('supervisor')) {
            return back()->with('error', 'You are not authorized to resend invites.');
        }

        foreach ($meeting->guest_emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\MeetingGuestInvite($meeting));
            }
        }

        return back()->with('success', 'Invites resent successfully!');
    }
}
