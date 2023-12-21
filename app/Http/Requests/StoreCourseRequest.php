<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="StoreCourseRequest",
 *     type="object",
 *     title="Store Course Request",
 *     @OA\Property(
 *     property="category_ids",
 *     type="array",
 *     @OA\Items(type="integer", example="1")),
 *     @OA\Property(property="title", type="string", maxLength=255, description="The title of the course."),
 *     @OA\Property(property="description", type="string", description="The description of the course."),
 *     @OA\Property(property="price", type="number", format="float", description="The price of the course."),
 *     @OA\Property(property="author", type="string", maxLength=255, description="The author of the course."),
 *     @OA\Property(property="thumbnail_image", type="file", description="Thumbnail image."),
 * )
 */
class StoreCourseRequest extends FormRequest
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
            'category_ids' => 'sometimes|array',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'author' => 'sometimes|string|max:255',
            'thumbnail_image' => 'sometimes|image|max:10240',
        ];
    }

    // Optionally, you could add custom messages or validation attribute names if necessary
}
