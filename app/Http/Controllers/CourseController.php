<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a paginated listing of courses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $pageNum = $request->input('pageNum', 1);
        $pageSize = $request->input('pageSize', 15);

        $courses = Course::paginate($pageSize, ['*'], 'page', $pageNum);

        return CourseResource::collection($courses);
    }


    /**
     * Store a newly created course in storage.
     *
     * Validates input and creates a new Course instance.
     *
     * @param  \App\Http\Requests\StoreCourseRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseRequest $request)
    {
        $validatedData = $request->validated();
        $course = Course::create($validatedData);
        return new CourseResource($course);
    }

    /**
     * Display the specified course.
     *
     * @param  \App\Models\Course  $course
     * @return \App\Http\Resources\CourseResource|\Illuminate\Http\JsonResponse
     */
    public function show($course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return new CourseResource($course);
    }



    /**
     * Update the specified course in storage.
     *
     * Validates input and updates the existing Course instance.
     *
     * @param  \App\Http\Requests\UpdateCourseRequest  $request
     * @param  int  $course_id
     * @return \App\Http\Resources\CourseResource|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateCourseRequest $request, $course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $validatedData = $request->validated();
        $course->update($validatedData);

        return new CourseResource($course);
    }


    /**
     * Remove the specified course from storage.
     *
     * Deletes a Course instance and returns a JSON response.
     *
     * @param  int  $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted'], 204);
    }
}
