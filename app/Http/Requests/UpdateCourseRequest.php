<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     schema="UpdateCourseRequest",
 *     type="object",
 *     title="Update Course Request",
 *     @OA\Property(property="title", type="string", maxLength=255, description="The updated title of the course."),
 *     @OA\Property(property="description", type="string", description="The updated description of the course."),
 *     @OA\Property(property="price", type="number", format="float", description="The updated price of the course."),
 *     @OA\Property(property="author", type="string", maxLength=255, description="The updated author of the course."),
 *     @OA\Property(property="thumbnail_image", type="file", description="Thumbnail image."),
 * )
 */
class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'author' => 'sometimes|required|string|max:255',
            'thumbnail_image' => 'sometimes|required|image|max:10240',
        ];
    }

    // Add custom messages or attribute names if needed
}
