<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
// use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\TopicResource;

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
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'topics' => TopicResource::collection($this->whenLoaded('topics')),
        ];
    }
}
