<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ChapterResource;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;

class ChapterController extends Controller
{
    /**
     * Display a listing of chapters.
     *
     * @OA\Get(
     *     path="/api/chapters",
     *     tags={"Chapters"},
     *     summary="Get a list of chapters",
     *     description="Gets a paginated list of chapters with associated course.",
     *     @OA\Response(response="200", description="Successful operation", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ChapterResource"))),
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $chapters = Chapter::with('course')->paginate();
            return ChapterResource::collection($chapters);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created chapter in storage.
     *
     * @OA\Post(
     *     path="/api/chapters",
     *     tags={"Chapters"},
     *     summary="Store a new chapter",
     *     description="Stores a new chapter with associated course.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreChapterRequest"),
     *     ),
     *     @OA\Response(response="200", description="Successful operation", @OA\JsonContent(ref="#/components/schemas/ChapterResource")),
     *     @OA\Response(response="500", description="Internal server error", @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))),
     * )
     *
     * @param  \App\Http\Requests\StoreChapterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChapterRequest $request)
    {
        try {
            $chapter = Chapter::create($request->validated());
            return new ChapterResource($chapter);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified chapter.
     *
     * @OA\Get(
     *     path="/api/chapters/{id}",
     *     tags={"Chapters"},
     *     summary="Get a specific chapter",
     *     description="Gets a specific chapter with associated course.",
     *     @OA\Parameter(name="id", in="path", required=true, description="ID of the chapter", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Successful operation", @OA\JsonContent(ref="#/components/schemas/ChapterResource")),
     *     @OA\Response(response="404", description="Chapter not found", @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))),
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $chapter = Chapter::with('course')->find($id);

            if (!$chapter) {
                return response()->json(['message' => 'Chapter not found'], Response::HTTP_NOT_FOUND);
            }

            return new ChapterResource($chapter);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified chapter in storage.
     *
     * @OA\Put(
     *     path="/api/chapters/{id}",
     *     tags={"Chapters"},
     *     summary="Update a specific chapter",
     *     description="Updates a specific chapter with associated course.",
     *     @OA\Parameter(name="id", in="path", required=true, description="ID of the chapter", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateChapterRequest"),
     *     ),
     *     @OA\Response(response="200", description="Successful operation", @OA\JsonContent(ref="#/components/schemas/ChapterResource")),
     *     @OA\Response(response="404", description="Chapter not found", @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))),
     * )
     *
     * @param  \App\Http\Requests\UpdateChapterRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChapterRequest $request, $id)
    {
        try {
            $chapter = Chapter::find($id);

            if (!$chapter) {
                return response()->json(['message' => 'Chapter not found'], Response::HTTP_NOT_FOUND);
            }

            $chapter->update($request->validated());
            return new ChapterResource($chapter);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified chapter from storage.
     *
     * @OA\Delete(
     *     path="/api/chapters/{id}",
     *     tags={"Chapters"},
     *     summary="Delete a specific chapter",
     *     description="Deletes a specific chapter.",
     *     @OA\Parameter(name="id", in="path", required=true, description="ID of the chapter", @OA\Schema(type="integer")),
     *     @OA\Response(response="204", description="No Content"),
     *     @OA\Response(response="404", description="Chapter not found", @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))),
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $chapter = Chapter::find($id);

            if (!$chapter) {
                return response()->json(['message' => 'Chapter not found'], Response::HTTP_NOT_FOUND);
            }

            $chapter->delete();

            return response()->json(['message' => 'Chapter deleted'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
