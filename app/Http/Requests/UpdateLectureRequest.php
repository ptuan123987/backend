<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateLectureRequest",
 *     type="object",
 *     title="Update Lecture Request",
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the lecture."
 *     ),
 *     @OA\Property(
 *         property="video",
 *         type="string",
 *         format="binary",
 *         description="Video file for the lecture in MP4, MOV, or AVI format."
 *     ),
 *     @OA\Property(
 *         property="thumbnail_img",
 *         type="string",
 *         format="binary",
 *         description="Thumbnail image for the lecture in JPEG, PNG, JPG, or GIF format."
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
            'title' => 'nullable|string|max:255',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:1048576',
            'thumbnail_img' => 'nullable|file|mimes:jpeg,png,jpg,gif',
            'resources' => 'nullable|array',
            'resources.*.title' => 'nullable|string|max:255',
            'resources.*.link' => 'nullable|string|max:255',
        ];
    }
}
