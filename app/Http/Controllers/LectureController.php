<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\LectureResourceModel;
use Illuminate\Http\Request;
use App\Http\Resources\LectureResource;
use App\Http\Requests\StoreLectureRequest;
use App\Http\Requests\UpdateLectureRequest;
use App\Jobs\PutVideoToS3;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
/**
 * @OA\Tag(
 *     name="lectures",
 *     description="Operations about lectures"
 * )
 *
 */
class LectureController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin', ['except' => ['index','show']]);
    }
    /**
     * @OA\Get(
     *     path="/api/lectures",
     *     tags={"lectures"},
     *     summary="List all lectures",
     *     description="Display a listing of the lectures",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/LectureResource")
     *         )
     *     ),
     * )
     */
    public function index()
    {
        $lectures = Lecture::with(['chapter', 'resources', 'video'])->get();
        return LectureResource::collection($lectures);
    }

    /**
     * @OA\Post(
     *     path="/api/lectures",
     *     tags={"lectures"},
     *     summary="Create a lecture",
     *     description="Store a newly created lecture in storage",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(ref="#/components/schemas/StoreLectureRequest")
     *     )),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/LectureResource")
     *     ),
     *     @OA\Response(response="500", description="Error creating lecture"),
     *  security={{"bearerAuth":{}}} )
     *
     * )
     */
    public function store(StoreLectureRequest $request)
    {
        DB::beginTransaction();

        try {
            $lecture = Lecture::create($request->validated());
            $resources = $request->get('resources');


            if ($resources) {
                foreach ($resources as $resourceData) {
                    $lecture->resources()->save($resourceData);
                }
            }

            DB::commit();

            $video = $request->file('video');
            $videoName = uniqid() . '_' . $video->getClientOriginalName();
            Storage::disk('public')->put($videoName, file_get_contents($video));
            PutVideoToS3::dispatch($lecture->id, $videoName);

            return new LectureResource($lecture);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/lectures/{id}",
     *     tags={"lectures"},
     *     summary="Show a specific lecture",
     *     description="Display the specified lecture",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of lecture to return",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="404", description="Lecture not found"),
     *
     * )
     */
    public function show($id)
    {
        $lecture = Lecture::with(['chapter', 'resources', 'video'])->find($id);

        if (!$lecture) {
            return response()->json(['message' => 'Lecture not found'], Response::HTTP_NOT_FOUND);
        }

        return new LectureResource($lecture);
    }

    /**
     * @OA\Put(
     *     path="/api/lectures/{id}",
     *     tags={"lectures"},
     *     summary="Update a lecture",
     *     description="Update the specified lecture in storage",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateLectureRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lecture updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/LectureResource")
     *     ),
     *     @OA\Response(response="404", description="Lecture not found"),
     *     @OA\Response(response="422", description="Invalid input"),
     *     @OA\Response(response="500", description="Error updating lecture"),
     *  security={{"bearerAuth":{}}} )
     *
     * )
     */
    public function update(UpdateLectureRequest $request)
    {
        try {
            $id = $request->input('id');
            $lecture = Lecture::find($id);

            if (!$lecture) {
                return response()->json(['message' => 'lecture not found'], Response::HTTP_NOT_FOUND);
            }

            $lecture->update($request->validated());
            if ($request->has('resources') && is_array($request->input('resources'))) {
                $currentResourceIds = $lecture->resources()->pluck('id')->toArray();

                $idsToKeep = [];

                foreach ($request->input('resources') as $resourceData) {
                    if (isset($resourceData['id']) && in_array($resourceData['id'], $currentResourceIds)) {
                        $lecture->resources()->where('id', $resourceData['id'])->update($resourceData);
                        $idsToKeep[] = $resourceData['id'];
                    } else {
                        $newResource = new LectureResourceModel($resourceData);
                        $lecture->resources()->save($newResource);
                        $idsToKeep[] = $newResource->id;
                    }
                }

                $idsToDelete = array_diff($currentResourceIds, $idsToKeep);
                LectureResourceModel::destroy($idsToDelete);
            }

            return new LectureResource($lecture);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/lectures/{id}",
     *     tags={"lectures"},
     *     summary="Delete a lecture",
     *     description="Remove the specified lecture from storage",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of lecture to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Lecture deleted"),
     *     @OA\Response(response="404", description="Lecture not found"),
     *  security={{"bearerAuth":{}}} )
     *
     * )
     */
    public function destroy($id)
    {
        $lecture = Lecture::find($id);

        if (!$lecture) {
            return response()->json(['message' => 'Lecture not found'], Response::HTTP_NOT_FOUND);
        }

        $lecture->delete();

        return response()->json(['message' => 'Lecture deleted'], Response::HTTP_NO_CONTENT);
    }
}
