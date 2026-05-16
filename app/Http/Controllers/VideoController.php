<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function __construct(protected VideoService $videoService) {}

    /**
     * Display a listing of videos.
     */
    public function index()
    {
        $videos = Video::with('uploader')->paginate(20);
        return Inertia::render('Videos/Index', [
            'videos' => $videos
        ]);
    }

    /**
     * Store a newly created video.
     */
    public function store(Request $request)
    {
        $this->authorize('video.upload');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi',
            'video_url' => 'nullable|url',
            'category_id' => 'nullable|exists:video_categories,id',
            'video_type' => 'required|in:short,long',
            'duration' => 'nullable|integer',
        ]);

        $video = $this->videoService->storeVideo($validated, $request->file('video_file'));

        return back()->with('success', 'Video uploaded successfully.');
    }

    /**
     * Display the specified video.
     */
    public function show(Video $video)
    {
        $this->authorize('video.view', $video);
        
        return Inertia::render('Videos/Show', [
            'video' => $video
        ]);
    }

    /**
     * Update the specified video.
     */
    public function update(Request $request, Video $video)
    {
        $this->authorize('video.update', $video);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'status' => 'string|in:active,inactive,processing',
        ]);

        $this->videoService->updateVideo($video, $validated);

        return back()->with('success', 'Video updated successfully.');
    }

    /**
     * Remove the specified video.
     */
    public function destroy(Video $video)
    {
        $this->authorize('video.delete', $video);

        $this->videoService->deleteVideo($video);

        return back()->with('success', 'Video deleted successfully.');
    }
}
