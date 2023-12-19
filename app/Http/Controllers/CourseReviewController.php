<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseReviewRequest; // Assuming this request file exists for validation
use App\Http\Resources\CourseReviewResource; // Assuming this resource file exists for JSON response formatting
use App\Models\CourseReview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CourseReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

        /**
     * @OA\Get(
     *  path="/api/user/course-reviews",
     *  summary="Get a list of course reviews",
     *  tags={"CourseReviews"},
     *  @OA\Parameter(
     *      name="pageNum",
     *      in="query",
     *      description="Page number",
     *      required=false,
     *      @OA\Schema(type="integer", example=1)
     *  ),
     *  @OA\Parameter(
     *      name="pageSize",
     *      in="query",
     *      description="Number of items per page",
     *      required=false,
     *      @OA\Schema(type="integer", example=15)
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="List of course reviews",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/CourseReviewResource")
     *      )
     *  ),
     *  security={{"bearerAuth":{}}}
     * )
     */
    public function index(Request $request)
    {
        $pageNum = $request->input('pageNum', 1);
        $pageSize = $request->input('pageSize', 15);
        $user = JWTAuth::parseToken()->authenticate();

        $courseReviews = CourseReview::with('course', 'user')->where('user_id', $user->id)->paginate($pageSize, ['*'], 'page', $pageNum);

        return CourseReviewResource::collection($courseReviews);
    }

    /**
     * @OA\Post(
     *  path="/api/user/course-reviews",
     *  summary="Create a new course review",
     *  tags={"CourseReviews"},
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/CourseReviewRequest")
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="New course review created",
     *      @OA\JsonContent(ref="#/components/schemas/CourseReviewResource")
     *  ),
     *  security={{"bearerAuth":{}}}
     * )
     */
    public function store(CourseReviewRequest $request)
    {
        $validated = $request->validated();

        $user = JWTAuth::parseToken()->authenticate();

        $courseReview = CourseReview::create([
            'course_id' => $validated['course_id'],
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'content' => $validated['content']
        ]);

        return new CourseReviewResource($courseReview);
    }

    /**
     * @OA\Delete(
     *  path="/api/user/course-reviews/{id}",
     *  summary="Delete a course review by ID",
     *  tags={"CourseReviews"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID of the course review",
     *      required=true,
     *      @OA\Schema(type="integer")
     *  ),
     *  @OA\Response(response=200, description="Course review deleted"),
     *  security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            try {
                $courseReview = CourseReview::findOrFail($id);

                if ($courseReview->user_id !== $user->id && !$user->hasRole('admin')) {
                    return response()->json([
                        'message' => "You don't have permission to delete this item"
                    ], Response::HTTP_FORBIDDEN);
                }

                $courseReview->delete();

                return response()->json([
                    'message' => 'Course review deleted successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Course review not found'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        return response()->json([
            'message' => 'Not authenticated to perform this action'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
