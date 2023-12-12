<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     type="object",
 *     title="Category Request",
 *     required={"name"},
 *     @OA\Property(property="parent_category_id", type="integer", format="int64", description="ID of the parent category, if any."),
 *     @OA\Property(property="name", type="string", description="Name of the category."),
 * )
 */
class CategoryRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        return true; // Change as per your authorization logic
    }

    // Get the validation rules that apply to the request.
    public function rules()
    {
        return [
            'parent_category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
        ];
    }
}
