<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateLectureRequest",
 *     type="object",
 *     title="Update Lecture Request",
 *     required={"chapter_id", "title"},
 *     @OA\Property(property="chapter_id", type="integer", format="int64", description="ID of the associated chapter."),
 *     @OA\Property(property="title", type="string", description="Title of the lecture."),
 *     @OA\Property(
 *         property="resources",
 *         type="array",
 *         description="List of updated resources for the lecture.",
 *         @OA\Items(
 *             type="object",
 *             required={"title", "link"},
 *             @OA\Property(property="title", type="string", description="Title of the resource."),
 *             @OA\Property(property="link", type="string", format="uri", description="Link to the resource.")
 *         )
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
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
            'resources' => 'sometimes|array',
            'resources.*.title' => 'required|string|max:255',
            'resources.*.link' => 'required|url',
        ];
    }
}
