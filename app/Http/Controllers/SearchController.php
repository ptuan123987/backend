<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchCoursesRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Log;
/**
 * @OA\Tag(
 *     name="Search",
 *     description="Operations about search"
 * )
 */
class SearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/search/courses",
     *     summary="Search courses",
     *     operationId="searchCourses",
     *     tags={"Search"},
     *     description="Search for courses with a search term, page number and size.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="searchTerm",
     *         in="query",
     *         required=true,
     *         description="The term to search for",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page_num",
     *         in="query",
     *         required=false,
     *         description="The number of the page to retrieve",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page_size",
     *         in="query",
     *         required=false,
     *         description="The size of the page (number of items per page)",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CourseResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function search_courses(SearchCoursesRequest $request)
    {
        $searchTerm = $request->get('searchTerm', '');

        $pageSize = $request->get('pageSize', 10);
        $pageNum = $request->get('page', 1);

        $courses = Course::search($searchTerm)
            ->with(['chapters.lectures' => function ($query) {
                $query->with('video')
                    ->orderBy('id', 'asc')
                    ->whereHas('video');
            }])
            ->paginate($pageSize, ['*'], 'page', $pageNum);

        return CourseResource::collection($courses);
    }
}
