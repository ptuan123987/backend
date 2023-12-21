<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;



class PutImageToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $courseId;
    protected $filename;

    public function __construct($courseId, $filename)
    {
        $this->courseId = $courseId;
        $this->filename = $filename;
    }

    public function handle()
    {
        try {
            $course = Course::find($this->courseId);
            $imgName = $this->filename;
            if ($course && Storage::disk('public')->exists($imgName)) {
                $imgPath = Storage::disk('public')->path($imgName);
                // Load the image from the public disk
                $manager = new ImageManager(new Driver());
                $image = $manager->read($imgPath);
                $image->resize(height: 320, width: 640);
                $encoded = $image->toJpg();
                Storage::disk('s3')->put('/thumbnails/' . $imgName, $encoded);

                Storage::disk('public')->delete($imgName);

                $cloudfrontUrl = env('AWS_CLOUDFRONT_ORIGIN');

                $course->thumbnail_url = $cloudfrontUrl . '/thumbnails/' . $imgName;
                $course->save();
            }
        } catch (\Exception $e) {
            Log::error('Error during video processing: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
}
