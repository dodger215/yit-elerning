<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class MeetingChatService
{
    /**
     * Get the chat history for a given meeting room.
     */
    public function getChatHistory(string $roomId): array
    {
        return Cache::get("meeting_chat_{$roomId}", []);
    }

    /**
     * Add a new message to the meeting's chat history.
     */
    public function addMessage(string $roomId, array $message): array
    {
        $chat = $this->getChatHistory($roomId);

        $message['id'] = uniqid('msg_');
        $message['created_at'] = now()->toIso8601String();

        $chat[] = $message;

        // Keep only the last 500 messages to prevent cache bloat
        if (count($chat) > 500) {
            $chat = array_slice($chat, -500);
        }

        // Store in cache for 24 hours
        Cache::put("meeting_chat_{$roomId}", $chat, now()->addHours(24));

        return $message;
    }

    // public function clearChatHistory (string $roomId)
    // {

    // }
}
