<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseReviewResource;

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
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'reviews' => CourseReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
