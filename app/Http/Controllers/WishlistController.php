<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\WishlistRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\WishlistResource;
use App\Models\Course;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;


class WishlistController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * @OA\Get(
     *     path="/api/user/wishlists",
     *     summary="Get a list of wishlists",
     *     tags={"Wishlists"},
     *     @OA\Parameter(
     *         name="pageNum",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of wishlists",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/WishlistResource")
     *         )
     *     ),
     *  security={{"bearerAuth":{}}} )
     * )
     */
    public function index(Request $request)
    {
        $pageNum = $request->input('pageNum', 1);
        $pageSize = $request->input('pageSize', 15);
        $user = JWTAuth::parseToken()->authenticate();

        $wishlists = $user->wishlists()->with('course')->paginate($pageSize, ['*'], 'page', $pageNum);

        return WishlistResource::collection($wishlists);
    }


    /**
     * @OA\Post(
     *     path="/api/user/wishlists",
     *     summary="Create a new wishlist item",
     *     tags={"Wishlists"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/WishlistRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="New wishlist item created",
     *         @OA\JsonContent(ref="#/components/schemas/WishlistResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *  security={{"bearerAuth":{}}} )
     * )
     */
    public function store(WishlistRequest $request)
    {
        $request = $request->validated();

        $course = Course::firstOrCreate(['title' => $request['course_name']]);

        $user = JWTAuth::parseToken()->authenticate();

        $wishlistItem = Wishlist::create([
            'course_id' => $course->id,
            'user_id' => $user->id,

        ]);
        return response()->json([
            'message' => 'Add course to wishlist successfully'
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/user/wishlists/{id}",
     *     summary="Delete a wishlist item by ID",
     *     tags={"Wishlists"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the wishlist item",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Wishlist item deleted"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found"),
     *  security={{"bearerAuth":{}}} )
     * )
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            try {
                $wishlist = Wishlist::findOrFail($id);
                $wishlist->delete();

                return response()->json([
                    'message' => 'Wishlist item deleted'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Wishlist item not found'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        return response()->json([
            'message' => 'Failed to delete wishlist item'
        ], Response::HTTP_FORBIDDEN);
    }
}
