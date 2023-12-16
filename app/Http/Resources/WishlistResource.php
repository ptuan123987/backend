<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="WishlistResource",
 *     type="object",
 *     title="Wishlist Resource",
 *
 *     @OA\Property(
 *         property="course",
 *         ref="#/components/schemas/CourseResource",
 *         description="Course information in the wishlist."
 *     ),
 * )
 */
class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'course' => new CourseResource($this->whenLoaded('course')),
        ];
    }
}
