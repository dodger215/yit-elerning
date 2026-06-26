<?php

namespace App\Http\Controllers;

use App\Services\MeetingChatService;
use Illuminate\Http\Request;

class MeetingChatController extends Controller
{
    public function __construct(public MeetingChatService $chatService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $roomId)
    {
        $messages = $this->chatService->getChatHistory($roomId);

        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $roomId)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'text' => 'required|string',
            'is_host' => 'boolean'
        ]);

        $message = $this->chatService->addMessage($roomId, [
            'sender_name' => $validated['sender_name'],
            'text' => $validated['text'],
            'is_host' => $validated['is_host'] ?? false,
        ]);

        return response()->json([
            'message' => $message,
        ], 201);
    }
}
