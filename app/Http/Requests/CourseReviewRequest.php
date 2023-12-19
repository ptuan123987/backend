<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CourseReviewRequest",
 *     type="object",
 *     required={"course_id", "rating"},
 *     properties={
 *         @OA\Property(
 *             property="course_id",
 *             type="integer",
 *             description="ID of the course to be reviewed",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="rating",
 *             type="number",
 *             format="float",
 *             description="Rating given to the course",
 *             example=4.5
 *         ),
 *         @OA\Property(
 *             property="content",
 *             type="string",
 *             description="Content of the course review",
 *             example="This course was very informative and well-structured."
 *         )
 *     },
 * )
 */
class CourseReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'rating' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, [1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5])) {
                        $fail('The ' . $attribute . ' is Invalid.');
                    }
                },
            ],
            'content' => 'required|string|max:1000',  // adjust string validation as needed
        ];
    }
}
