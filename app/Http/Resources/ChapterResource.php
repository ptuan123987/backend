<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ChapterResource",
 *     title="Chapter Resource",
 *     description="Represents a chapter resource.",
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the chapter."),
 *     @OA\Property(property="course_id", type="integer", description="The unique identifier of the course associated with the chapter."),
 *     @OA\Property(property="title", type="string", description="The title of the chapter."),
 *     @OA\Property(
 *         property="lectures",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/LectureResource"),
 *         description="A collection of lectures related to the chapter."
 *     ),
 * )
 */
class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'title' => $this->title,
            'lectures' => LectureResource::collection($this->whenLoaded('lectures')),
        ];
    }
}
