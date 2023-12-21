<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChapterResource;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseReviewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Jobs\PutImageToS3;

/**
 * @OA\Tag(
 *     name="Courses",
 *     description="Endpoints for managing courses"
 * )
 */
class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin', ['except' => ['index','show','get_reviews', 'get_chapters']]);
    }
    /**
     * Display a paginated listing of courses.
     *
     * @OA\Get(
     *     path="/api/courses",
     *     summary="Get a paginated listing of courses",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="pageNum",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CourseResource")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/courses",
     *     summary="Store a newly created course",
     *     tags={"Courses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(ref="#/components/schemas/StoreCourseRequest")
     *     )),
     *     @OA\Response(
     *         response=201,
     *         description="Course created successfully",
     *         @OA\JsonContent()
     *     ),
     *  security={{"bearerAuth":{}}} )
     *
     * )
     */
    public function store(StoreCourseRequest $request)
    {
        $validatedData = $request->validated();

        $categoryIds = $validatedData['category_ids'];
        unset($validatedData['category_ids']);

        $course = Course::create($validatedData);

        if ($course) {
            $course->categories()->sync($categoryIds);
        }

        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $filename = uniqid() . $file->getClientOriginalName();
            Storage::disk('public')->put($filename, file_get_contents($file));
            PutImageToS3::dispatch($course->id, $filename);
        }

        return new CourseResource($course);
    }

   /**
     * Display the specified course.
     *
     * @OA\Get(
     *     path="/api/courses/{course_id}",
     *     summary="Display the specified course",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="course_id",
     *         in="path",
     *         description="ID of the course",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CourseResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     )
     * )
     */

    private function totalVideoDurationAttribute($chapters)
    {
        $totalDuration = $chapters->reduce(function ($carry, $chapter) {
        // Assuming each lecture has one or more associated videos and the 'video_duration' field is on the video model
            foreach ($chapter->lectures as $lecture) {
                if (!$lecture->video) {
                    continue;
                }
                $carry += $lecture->video->duration;
            }
            return $carry;
        }, 0);
    return $totalDuration;
    }
    public function show($course_id)
    {
        $course = Course::with('chapters.lectures.video')->find($course_id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        $course->total_video_duration = $this->totalVideoDurationAttribute($course->chapters);
        return new CourseResource($course);
    }

    /**
     * Update the specified course in storage.
     *
     * @OA\Post(
     *     path="/api/courses/{course_id}",
     *     summary="Update the specified course",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="course_id",
     *         in="path",
     *         description="ID of the course",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *        name="_method",
     *        in="query",
     *        required=false,
     *        description="Method to be used",
     *        @OA\Schema(type="string", default="PUT")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(ref="#/components/schemas/UpdateCourseRequest")
     *     )),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CourseResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     *  security={{"bearerAuth":{}}}
     * )
     */
    public function update(UpdateCourseRequest $request, $course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $validatedData = $request->validated();
        $course->update($validatedData);

        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $filename = uniqid() . $file->getClientOriginalName();
            Storage::disk('public')->put($filename, file_get_contents($file));
            PutImageToS3::dispatch($course->id, $filename);
        }

        return new CourseResource($course);
    }


   /**
     * Remove the specified course from storage.
     *
     * @OA\Delete(
     *     path="/api/courses/{course_id}",
     *     summary="Remove the specified course",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="course_id",
     *         in="path",
     *         description="ID of the course",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Course deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     *  security={{"bearerAuth":{}}} )
     *
     * )
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

    /**
     * Display a listing of reviews for the specified course.
     *
     * @OA\Get(
     *     path="/api/courses/{course_id}/reviews",
     *     summary="Display a listing of reviews for the specified course",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="course_id",
     *         in="path",
     *         description="ID of the course",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="pageNum",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CourseReviewResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     )
     * )
     */
    public function get_reviews($course_id, $pageNum = 1, $pageSize = 10)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $reviews = $course->reviews()->with('user')->paginate($pageSize, ['*'], 'page', $pageNum);

        return CourseReviewResource::collection($reviews);
    }


    /**
     * Display a listing of chapters for the specified course.
     *
     * @OA\Get(
     *     path="/api/courses/{course_id}/chapters",
     *     summary="Display a listing of chapters for the specified course",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="course_id",
     *         in="path",
     *         description="ID of the course",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ChapterResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     * )
     */
    public function get_chapters($course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $chapters = $course->chapters()->with('lectures')->get();

        return ChapterResource::collection($chapters);
    }
}
