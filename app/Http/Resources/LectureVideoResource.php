<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="LectureVideoResource",
 *     description="Lecture video resource",
 *     @OA\Xml(name="LectureVideoResource")
 * )
 */
class LectureVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     *
     * @OA\Property(
     *     property="data",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         example=1,
     *         description="Unique identifier for the lecture video"
     *     ),
     *     @OA\Property(
     *         property="url",
     *         type="string",
     *         example="http://example.com/video.mp4",
     *         description="URL of the video"
     *     ),
     *     @OA\Property(
     *         property="thumbnail_url",
     *         type="string",
     *         example="http://example.com/thumbnail.jpg",
     *         description="Thumbnail URL of the video"
     *     ),
     *     @OA\Property(
     *         property="duration",
     *         type="integer",
     *         example=3600,
     *         description="Duration of the video in seconds"
     *     ),
     *     @OA\Property(
     *         property="lecture_id",
     *         type="integer",
     *         example=10,
     *         description="Foreign key to Lecture"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date-time",
     *         description="Creation date of the video"
     *     ),
     *     @OA\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date-time",
     *         description="Last update date of the video"
     *     )
     * )
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'thumbnail_url' => $this->thumbnail_url,
            'duration' => $this->duration,
            'lecture_id' => $this->lecture_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
