<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Update Chapter Request",
 *     description="Update Chapter Request body data",
 *     type="object",
 *     required={"course_id", "title"}
 * )
 */
class UpdateChapterRequest extends FormRequest
{
    /**
     * @OA\Property(
     *     property="course_id",
     *     description="ID of the associated course",
     *     type="integer"
     * )
     *
     * @var int
     */
    public $course_id;

    /**
     * @OA\Property(
     *     property="title",
     *     description="Title of the chapter",
     *     type="string"
     * )
     *
     * @var string
     */
    public $title;

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
     * @return array
     */
    public function rules()
    {
        return [
            'course_id' => 'required|integer',
            'title' => 'required|string',
        ];
    }
}
