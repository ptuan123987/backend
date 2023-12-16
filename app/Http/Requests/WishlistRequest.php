<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 * @OA\Schema(
 *     schema="WishlistRequest",
 *     type="object",
 *     title="Wishlist Request",
 *     required={"course_name"},
 *     @OA\Property(property="course_name", type="string", description="Name of the course in the wishlist."),
 * )
 */
class WishlistRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'course_name' => 'string|required'
        ];
    }
}
