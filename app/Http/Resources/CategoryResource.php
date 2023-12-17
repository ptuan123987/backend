<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
// use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\TopicResource;

/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     title="Category Resource",
 *     description="Represents a category resource.",
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the category."),
 *     @OA\Property(property="parent_category", type="object", description="The parent category.", ref="#/components/schemas/CategoryResource"),
 *     @OA\Property(property="name", type="string", description="The name of the category."),
 *     @OA\Property(property="courses", type="array", description="List of courses associated with the category.", @OA\Items(ref="#/components/schemas/CourseResource")),
 *     @OA\Property(property="topics", type="array", description="List of topics associated with the category.", @OA\Items(ref="#/components/schemas/TopicResource")),
 * )
 */
class CategoryResource extends JsonResource
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
            'parent_category' => new CategoryResource($this->whenLoaded('parentCategory')),
            'name' => $this->name,
            'subcategories' => CategoryResource::collection($this->whenLoaded('subcategories')),
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'topics' => TopicResource::collection($this->whenLoaded('topics')),
        ];
    }
}
