<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\TopicResource;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Endpoints for managing categories"
 * )
 */
class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.admin', ['except' => ['index','show']]);
    }
    /**
     * Get a list of categories
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get a list of categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::with(['parentCategory', 'topics', 'subcategories', 'subcategories.topics'])->get();
        return CategoryResource::collection($categories);
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Store a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CategoryRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *
     *     ),
     *      security={{"bearerAuth":{}}} )
     * )
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified category
     *
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Display the specified category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function show($id)
    {
        $category = Category::with(['parentCategory', 'topics'])->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return new CategoryResource($category);
    }

    /**
     * Update the specified category
     *
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update the specified category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *      security={{"bearerAuth":{}}} )
     * )
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $category->update($request->validated());

        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Remove the specified category
     *
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Remove the specified category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category removed successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *      security={{"bearerAuth":{}}} )
     * )
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

        /**
     * @OA\Get(
     *     path="/api/categories/{categoryId}/topics",
     *     summary="Get Topics by Category",
     *     tags={"Categories"},
     *     description="Returns all topics under a specific category",
     *     operationId="getTopics",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID of category to return topics for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TopicResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     security={{"bearerAuth":{}}} )
     * )
     */
    public function getTopics($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return TopicResource::collection($category->topics);
    }

        /**
     * @OA\Get(
     *     path="/api/categories/{categoryId}/courses",
     *     summary="Get Courses by Category",
     *     tags={"Categories"},
     *     description="Returns all courses under a specific category",
     *     operationId="getCourses",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID of category to return courses for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CourseResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     security={{"bearerAuth":{}}} )
     * )
     */
    public function getCourses($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return CourseResource::collection($category->courses);
    }

        /**
     * Get Subcategories by Category
     *
     * @OA\Get(
     *     path="/api/categories/{categoryId}/subcategories",
     *     summary="Get Subcategories by Category",
     *     tags={"Categories"},
     *     description="Returns all subcategories under a specific category",
     *     operationId="getSubcategories",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID of category to return subcategories for",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getSubcategories($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return CategoryResource::collection($category->subcategories);
    }

        /**
     * Attach a Course to a Category
     *
     * @OA\Patch(
     *     path="/api/categories/{categoryId}/courses/{courseId}",
     *     summary="Attach a Course to a Category",
     *     tags={"Categories"},
     *     description="Attaches an existing course to a specific category",
     *     operationId="attachCourse",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="ID of the category to which the course is to be attached",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="courseId",
     *         in="path",
     *         description="ID of the course to attach to the category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course attached successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Course attached")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category or Course not found"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function attachCourse($id, $courseId)
    {
        $category = Category::find($id);

        if ($category->courses()->where('course_id', $courseId)->exists()) {
            return response()->json(['message' => 'Course already attached'], Response::HTTP_CONFLICT);
        }

        $category->courses()->attach($courseId);

        return response()->json(['message' => 'Course attached'], Response::HTTP_OK);
    }
}
