<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Courses",
 *     description="Endpoints for managing courses"
 * )
 */
class CourseController extends Controller
{
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
     *         @OA\JsonContent(ref="#/components/schemas/StoreCourseRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course created successfully",
     *         @OA\JsonContent()
     *     )
     * )
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
     * @OA\Put(
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCourseRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CourseResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     )
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
     *     )
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
}
