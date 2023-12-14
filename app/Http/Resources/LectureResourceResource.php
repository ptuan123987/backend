<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="LectureResourceModelResource",
 *     description="Lecture resource model resource",
 *     @OA\Xml(
 *         name="LectureResourceModelResource"
 *     )
 * )
 */
class LectureResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     *
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="ID of the lecture resource"
     * ),
     * @OA\Property(
     *     property="lecture_id",
     *     type="integer",
     *     description="The ID of the lecture associated with this resource"
     * ),
     * @OA\Property(
     *     property="title",
     *     type="string",
     *     description="Title of the lecture resource"
     * ),
     * @OA\Property(
     *     property="link",
     *     type="string",
     *     format="uri",
     *     description="A link to the lecture resource"
     * )
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'lecture_id' => $this->lecture_id,
            'title' => $this->title,
            'link' => $this->link,
        ];
    }
}
