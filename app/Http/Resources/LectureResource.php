<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="LectureResource",
 *     title="Lecture Resource",
 *     description="Represents a lecture resource.",
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the lecture."),
 *     @OA\Property(property="chapter_id", type="integer", description="The unique identifier of the chapter associated with the lecture."),
 *     @OA\Property(property="title", type="string", description="The title of the lecture."),
 *     @OA\Property(
 *         property="resources",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/LectureResourceResource"),
 *         description="A collection of resources related to the lecture."
 *     ),
 *     @OA\Property(property="video", ref="#/components/schemas/LectureVideoResource", description="The associated video resource for the lecture."),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="The date and time when the lecture was created."),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="The date and time when the lecture was last updated.")
 * )
 */
class LectureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'video' => new LectureVideoResource($this->whenLoaded('video')),
            'resources' => LectureResourceResource::collection($this->whenLoaded('resources')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
