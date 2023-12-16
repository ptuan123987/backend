<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateLectureRequest",
 *     type="object",
 *     title="Update Lecture Request",
 *     @OA\Property(property="id", type="integer", format="int64", description="ID of the lecture."),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the lecture."
 *     ),
 *     @OA\Property(
 *         property="resources",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"title", "link"},
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 description="Title of the resource."
 *             ),
 *             @OA\Property(
 *                 property="link",
 *                 type="string",
 *                 format="uri",
 *                 description="Link to the resource."
 *             )
 *         ),
 *         description="List of updated resources for the lecture."
 *     ),
 * )
 */
class UpdateLectureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Adjust the authorization logic based on your application's requirements
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    return [
            'id' => 'required|exists:lectures',
            'title' => 'nullable|string|max:255',
            'resources' => 'nullable|array',
            'resources.*.title' => 'nullable|string|max:255',
            'resources.*.link' => 'nullable|string|max:255',
        ];
    }
}
