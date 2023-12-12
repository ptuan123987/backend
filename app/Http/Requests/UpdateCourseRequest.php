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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'author' => 'sometimes|string|max:255',
        ];
    }

    // Add custom messages or attribute names if needed
}
