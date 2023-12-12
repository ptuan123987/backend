<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TopicResource",
 *     title="Topic Resource",
 *     description="Represents a topic resource.",
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the topic."),
 *     @OA\Property(property="name", type="string", description="The name of the topic."),
 *     @OA\Property(property="categories", type="array", description="List of categories associated with the topic.", @OA\Items(ref="#/components/schemas/CategoryResource")),
 * )
 */
class TopicResource extends JsonResource
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
            'name' => $this->name,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
