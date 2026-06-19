<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Services\MeetingService;
use Illuminate\Support\Facades\Auth;


class MeetingController extends Controller
{

    public function __construct(protected MeetingService $meetingService) {}


    public function index()
    {
        $meetings = Meeting::where('meeting_type', 'public')
                    ->orderBy('start_time', 'asc')
                    ->get();

        return response()->json([
            'meetings' => $meetings,
        ]);
    }


    public function join(Request $request, string $roomId)
    {
        $meeting = Meeting::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        // Guest handling for Public meetings
        if (! $user) {
            if ($meeting->meeting_type !== 'public') {
                return response()->json(['error' => 'Authentication required for this meeting.']);
            }

            // For guests joining public meetings, we need a display name
            $guestName = $request->query('guest_name');
            if (! $guestName) {
                return response()->json(["meeting" => $meeting]);
            }

            return response()->json([
                'meeting' => $meeting,
                'participantName' => $guestName,
                'isHost' => false,
                'isGuest' => true,
            ]);
        }

        // Authorize access for authenticated users
        if (! $this->meetingService->authorizeJoin($meeting, $user)) {
            return response()->json(['error'=> 'You are not authorized to join this meeting.']);
        }

        $isHost = $this->meetingService->isHost($meeting, $user);

        return response()->json([
            'meeting' => $meeting,
            'participantName' => $user->username ?? $user->email,
            'isHost' => $isHost,
        ]);
    }


    public function ended(Request $request, string $roomId)
    {
        $meeting = Meeting::where('room_id', $roomId)->firstOrFail();
        $isGuest = Auth::check();

        $returnUrl = $isGuest
            ? url('/')
            : route('meetings.index');

        return response()->json([
            'meeting' => $meeting,
            'returnUrl' => $returnUrl,
            'isGuest' => $isGuest,
        ]);
    }

}
