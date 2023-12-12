<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="Represents a user resource.",
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the user."),
 *     @OA\Property(property="display_name", type="string", description="The display name of the user."),
 *     @OA\Property(property="email", type="string", format="email", description="The email address of the user."),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="The timestamp when the user was created."),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="The timestamp when the user was last updated."),
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'email' => $this->email,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
