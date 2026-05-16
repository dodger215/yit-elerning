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
        $videoPath = $file ? $file->store('videos', 'public') : ($data['video_url'] ?? null);

        return Video::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'video_url' => $videoPath,
            'thumbnail_url' => $data['thumbnail_url'] ?? null,
            'duration_seconds' => $data['duration_seconds'] ?? 0,
            'size_bytes' => $file ? $file->getSize() : 0,
            'mime_type' => $file ? $file->getMimeType() : 'video/mp4',
            'status' => $data['status'] ?? 'processing',
            'uploader_id' => auth()->id(),
            'category_id' => $data['category_id'] ?? null,
        ]);
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
