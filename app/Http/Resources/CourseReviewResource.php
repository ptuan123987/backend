<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
/**
 * @OA\Schema(
 *     schema="CourseReviewResource",
 *     title="Course Review Resource",
 *     description="Represents a course review resource.",
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the review."),
 *     @OA\Property(property="user", type="object", description="The user who created the review.", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="rating", type="integer", description="The rating given in the review."),
 *     @OA\Property(property="content", type="string", description="The content of the review."),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="The timestamp when the review was created."),
 * )
 */
class CourseReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'rating' => $this->rating,
            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
