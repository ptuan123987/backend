<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Lecture;

class PutVideoToS3 implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $lectureId;
    protected $videoContent;

    public function __construct($lectureId, $videoContent)
    {
        $this->lectureId = $lectureId;
        $this->videoContent = $videoContent;
    }

    public function handle()
    {
        $lecture = Lecture::find($this->lectureId);

        if ($lecture && $this->videoContent) {
            Storage::disk('s3')->put('videos/' . $lecture->video_path, $this->videoContent);
            $lecture->update(['video_url' => Storage::disk('s3')->url('videos/' . $lecture->video_path)]);
        }
    }
}

