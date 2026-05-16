<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessVideoConversion implements ShouldQueue
{
    use Queueable;

    public $video;
    public $originalPath;

    /**
     * Create a new job instance.
     */
    public function __construct(\App\Models\Video $video, string $originalPath)
    {
        $this->video = $video;
        $this->originalPath = $originalPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Generate WebP Thumbnail
        $thumbnailPath = 'thumbnails/' . $this->video->id . '.webp';
        \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('public')
            ->open($this->originalPath)
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('public')
            ->save($thumbnailPath);

        // 2. Convert video to WebM
        $webmPath = 'videos/' . $this->video->id . '.webm';
        
        $format = new \FFMpeg\Format\Video\WebM();
        // Optimize for web
        $format->setKiloBitrate(1000)
               ->setAudioKiloBitrate(128);

        \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('public')
            ->open($this->originalPath)
            ->export()
            ->toDisk('public')
            ->inFormat($format)
            ->save($webmPath);

        // 3. Update database
        $this->video->update([
            'video_url' => $webmPath,
            'thumbnail_url' => '/storage/' . $thumbnailPath,
            'status' => 'active', // Finished processing
        ]);

        // 4. Clean up original file to save space
        \Illuminate\Support\Facades\Storage::disk('public')->delete($this->originalPath);
    }
}
