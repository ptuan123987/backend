<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Store Topic Request",
 *     description="Request body data for creating a new topic",
 *     type="object",
 *     required={"category_id","name"},
 *     @OA\Property(property="category_id", type="integer", description="ID of the category to which the topic belongs."),
 *     @OA\Property(property="name", type="string", maxLength=255, description="Name of the new topic."),
 * )
 */
class UpdateTopicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255'
        ];
    }
}
