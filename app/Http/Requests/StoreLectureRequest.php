<?php
/**
 * @OA\Schema(
 *     schema="StoreLectureRequest",
 *     type="object",
 *     title="Store Lecture Request",
 *     required={"chapter_id", "title", "video"},
 *     @OA\Property(property="chapter_id", type="integer", format="int64", description="ID of the associated chapter."),
 *     @OA\Property(property="title", type="string", description="Title of the lecture."),
 *     @OA\Property(property="video", type="string", format="uri", description="Link to the video."),
 *     @OA\Property(property="thumbnail_img", type="string", format="uri", description="Link to the thumbnail image."),
 *     @OA\Property(
 *         property="resources",
 *         type="array",
 *         description="List of resources for the lecture.",
 *         @OA\Items(
 *             type="object",
 *             required={"title", "link"},
 *             @OA\Property(property="title", type="string", description="Title of the resource."),
 *             @OA\Property(property="link", type="string", format="uri", description="Link to the resource.")
 *         )
 *     ),
 * )
 */
class StoreLectureRequest extends FormRequest
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
            'video' => 'required|url',
            'thumbnail_img' => 'nullable|url',
            'resources' => 'sometimes|array',
            'resources.*.title' => 'required|string|max:255',
            'resources.*.link' => 'required|url',
        ];
    }
}
