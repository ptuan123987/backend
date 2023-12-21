<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseReviewResource;
use App\Http\Resources\ChapterResource;

/**
 * @OA\Schema(
 *     schema="CourseResource",
 *     title="Course Resource",
 *     description="Represents a course resource.",
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the course."),
 *     @OA\Property(property="title", type="string", description="The title of the course."),
 *     @OA\Property(property="description", type="string", description="The description of the course."),
 *     @OA\Property(property="price", type="number", description="The price of the course."),
 *     @OA\Property(property="author", type="string", description="The author of the course."),
 *     @OA\Property(property="categories", type="array", description="List of categories associated with the course.", @OA\Items(ref="#/components/schemas/CategoryResource")),
 *     @OA\Property(property="reviews", type="array", description="List of reviews associated with the course.", @OA\Items(ref="#/components/schemas/CourseReviewResource")),
 * )
 */
class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'author' => $this->author,
            'thumbnail_url' => $this->thumbnail_url,
            'reviews' => CourseReviewResource::collection($this->whenLoaded('reviews')),
            'chapters' => ChapterResource::collection($this->whenLoaded('chapters')),
            'total_video_duration' => $this->when(isset($this->total_video_duration), $this->total_video_duration),
        ];
    }
}
