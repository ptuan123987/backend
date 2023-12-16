<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Http\Resources\TopicResource;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Topics",
 *     description="Endpoints for managing topics"
 * )
 */

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin', ['except' => ['index','show']]);
    }
    /**
     * Display a listing of topics.
     *
     * @OA\Get(
     *     path="/api/topics",
     *     tags={"Topics"},
     *     summary="Get a list of topics",
     *     description="Gets a paginated list of topics with categories.",
     *     @OA\Response(response="200", description="Successful operation", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TopicResource"))),
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::with('categories')->get();
        return TopicResource::collection($topics);
    }

    /**
     * Store a newly created topic in storage.
     *
     * @OA\Post(
     *     path="/api/topics",
     *     tags={"Topics"},
     *     summary="Create a new topic",
     *     description="Creates a new topic with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTopicRequest"),
     *     ),
     *     @OA\Response(response="200", description="Successful operation", @OA\JsonContent(ref="#/components/schemas/TopicResource")),
     *     @OA\Response(response="409", description="Topic already exists"),
     *  security={{"bearerAuth":{}}} )
     *
     * )
     *
     * @param  \App\Http\Requests\StoreTopicRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTopicRequest $request)
    {
        $validatedData = $request->validated();
        $topic = Topic::where('name', $validatedData['name'])->first();

        if ($topic) {
            return response()->json(['message' => 'Topic already exists'], Response::HTTP_CONFLICT);
        }

        $topic = Topic::create($validatedData);

        if ($request->has('category_id')) {
            $topic->categories()->attach($request->category_id);
        }

        return new TopicResource($topic);
    }

    /**
     * Display the specified topic.
     *
     * @OA\Get(
     *     path="/api/topics/{id}",
     *     tags={"Topics"},
     *     summary="Get a specific topic",
     *     description="Gets details of a specific topic by ID.",
     *     @OA\Parameter(name="id", in="path", required=true, description="Topic ID", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Successful operation", @OA\JsonContent(ref="#/components/schemas/TopicResource")),
     *     @OA\Response(response="404", description="Topic not found"),
     *
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($topic);
    }

    /**
     * Update the specified topic in storage.
     *
     * @OA\Put(
     *     path="/api/topics/{id}",
     *     tags={"Topics"},
     *     summary="Update a specific topic",
     *     description="Updates a specific topic by ID with the provided data.",
     *     @OA\Parameter(name="id", in="path", required=true, description="Topic ID", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTopicRequest"),
     *     ),
     *     @OA\Response(response="200", description="Successful operation", @OA\JsonContent(ref="#/components/schemas/TopicResource")),
     *     @OA\Response(response="404", description="Topic not found"),
     *      security={{"bearerAuth":{}}} )
     * )
     *
     * @param  \App\Http\Requests\UpdateTopicRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTopicRequest $request, $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validated();
        $topic->update($validatedData);

        return response()->json($topic);
    }

    /**
     * Remove the specified topic from storage.
     *
     * @OA\Delete(
     *     path="/api/topics/{id}",
     *     tags={"Topics"},
     *     summary="Delete a specific topic",
     *     description="Deletes a specific topic by ID.",
     *     @OA\Parameter(name="id", in="path", required=true, description="Topic ID", @OA\Schema(type="integer")),
     *     @OA\Response(response="204", description="Successful operation with no content"),
     *     @OA\Response(response="404", description="Topic not found"),
     *      security={{"bearerAuth":{}}} )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json(['message' => 'Topic not found'], Response::HTTP_NOT_FOUND);
        }

        $topic->delete();

        return response()->json(['message' => 'Topic deleted'], Response::HTTP_NO_CONTENT);
    }
}
