<?php

namespace App\Jobs;

use FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Lecture;
use \getID3;
use App\Models\LectureVideo;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class PutVideoToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lectureId;
    protected $filename;
    protected $is_update;

    public function __construct($lectureId, $filename, $is_update = false)
    {
        $this->lectureId = $lectureId;
        $this->filename = $filename;
        $this->is_update = $is_update;
    }

    public function handle()
    {
        try {
            $lecture = Lecture::find($this->lectureId);
            $videoName = $this->filename;
            if ($lecture && Storage::disk('public')->exists($videoName)) {
                $videoPath = Storage::disk('public')->path($videoName);

                // Get video duration using getID3
                $getID3 = new getID3();
                $fileInfo = $getID3->analyze($videoPath);
                $durationSeconds = $fileInfo['playtime_seconds'] ?? null;

                // Create and upload the thumbnail
                $thumbnailPath = $this->createThumbnail($videoName);
                Log::alert("message: " . $thumbnailPath);
                // Upload the video to S3
                $videoContents = file_get_contents($videoPath);
                Storage::disk('s3')->put('videos/' . $videoName, $videoContents);

                // Delete the local video file after uploading to S3
                Storage::disk('public')->delete($videoName);

                // Get the CloudFront URL from environment
                $cloudfrontUrl = env('AWS_CLOUDFRONT_ORIGIN');

                // Check if this is an update to an existing lecture video
                if ($this->is_update) {
                    LectureVideo::where('lecture_id', $lecture->id)->delete();
                }

                // Create a new LectureVideo record
                LectureVideo::create([
                    'lecture_id' => $lecture->id,
                    'url' => $cloudfrontUrl . '/videos/' . $videoName,
                    'thumbnail_url' => $cloudfrontUrl . '/videos/thumbnails/' . basename($thumbnailPath),
                    'duration' => $durationSeconds,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error during video processing: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }

    private function createThumbnail($videoName)
    {
        $thumbnailName = 'thumbnail-' . time() . '.jpg';

        FFMpeg::fromDisk('public')
            ->open($videoName)
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('s3')
            ->save('videos/thumbnails/' . $thumbnailName);

        return $thumbnailName;
    }
}
