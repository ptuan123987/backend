<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Requests\CourseCreateRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Storage;


class CourseController extends Controller
{
    use HttpResponses;

    /**
     * Show the form for creating a new resource.
     */
    public function create(CourseCreateRequest $request)
    {
        $cloudfrontOrigin = env('CLOUDFRONT_ORIGIN');

        $video = $request->file('video');
        $filename = $video->getClientOriginalName();

        $filePath = $filename;
        $response = Storage::disk('s3')->put($filePath, file_get_contents($video));

        try {
            $response = Storage::disk('s3')->put($filePath, file_get_contents($video), 'public');
            $videoUrl = $cloudfrontOrigin . '/' . $filePath;

            return $this->success('Video uploaded successfully! URL: ' . $videoUrl, 'success');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'error');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
