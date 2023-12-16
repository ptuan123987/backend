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
use Illuminate\Support\Facades\Log;

class PutVideoToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lectureId;
    protected $filename;
    protected $is_update;

    public function __construct($lectureId, $filename, $is_update=false)
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
                $video = Storage::disk('public')->get($videoName);
                $videoPath = Storage::disk('public')->path($videoName);

                $getID3 = new getID3();
                $fileContents = file_get_contents($videoPath);

                $fileInfo = $getID3->analyze($videoPath);
                $durationSeconds = $fileInfo['playtime_seconds'] ?? null;

                $cloudfrontUrl = env('AWS_CLOUDFRONT_ORIGIN');

                Storage::disk('s3')->put('videos/' . $videoName, $fileContents);
                Storage::disk('public')->delete($videoName);

                if ($this->is_update) {
                    $lecture->video()->delete();
                }

                LectureVideo::create([
                    'lecture_id' => $lecture->id,
                    'url' => $cloudfrontUrl . '/videos/' . $videoName,
                    'thumbnail_url' => $cloudfrontUrl . '/videos/' . $videoName . '.jpg',
                    'duration' => $durationSeconds,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error message: '.$e);
       }
    }
}
