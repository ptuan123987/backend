<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Lecture;
use \getID3;
use App\Models\LectureVideo;
use Illuminate\Foundation\Bus\Dispatchable;


class PutVideoToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lectureId;
    protected $videoPath; // Ensure this property is set for the video path

    public function __construct($lectureId, $videoPath)
    {
        $this->lectureId = $lectureId;
        $this->videoPath = $videoPath;
    }

    public function handle()
    {
        $lecture = Lecture::find($this->lectureId);
        if ($lecture && Storage::disk('local')->exists($this->videoPath)) { // Check if the video exists in local storage
            $videoName = basename($this->videoPath); // Get the base name of the file
            $getID3 = new getID3();

            // Read the file from the local disk
            $filePath = storage_path('public/' . $this->videoPath);
            $fileContents = file_get_contents($filePath);

            $fileInfo = $getID3->analyze($filePath); // Use the local path for analysis
            $durationSeconds = $fileInfo['playtime_seconds'] ?? null;
            $cloudfrontUrl = env('AWS_CLOUDFRONT_ORIGIN');

            // Now store the content to S3
            Storage::disk('s3')->put('videos/' . $videoName, $fileContents);

            // Create a record for the uploaded video with its CloudFront URL
            LectureVideo::create([
                'lecture_id' => $lecture->id,
                'url' => $cloudfrontUrl . '/videos/' . $videoName, // You might need to adjust this URL based on actual path structure
            ]);
        }
    }
}
