<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService
{
    /**
     * Store a newly uploaded video.
     */
    public function storeVideo(array $data, $file = null): Video
    {
        $videoPath = $file ? $file->store('temp_videos', 'public') : ($data['video_url'] ?? null);

        $video = Video::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'video_url' => $videoPath,
            'duration' => $data['duration'] ?? 0,
            'file_size' => $file ? $file->getSize() : 0,
            'status' => 'processing',
            'video_type' => $data['video_type'] ?? 'long',
            'uploader_id' => auth()->id(),
            'category_id' => $data['category_id'] ?? null,
        ]);

        if ($file) {
            \App\Jobs\ProcessVideoConversion::dispatch($video, $videoPath);
        } else {
            $video->update(['status' => 'active']);
        }

        return $video;
    }

    /**
     * Update video details.
     */
    public function updateVideo(Video $video, array $data): Video
    {
        $video->update($data);
        return $video;
    }

    /**
     * Delete a video and its file.
     */
    public function deleteVideo(Video $video): bool
    {
        if ($video->video_url && Storage::disk('public')->exists($video->video_url)) {
            Storage::disk('public')->delete($video->video_url);
        }

        return $video->delete();
    }
}
