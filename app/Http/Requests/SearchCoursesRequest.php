<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Search Request",
 *     description="Search request body data",
 *     type="object",
 *     required={"searchTerm"}
 * )
 */
class SearchCoursesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Only allow authenticated users to execute this request
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     * @OA\Property(
     *     property="searchTerm",
     *     type="string",
     *     description="The term to search for"
     * )
     *
     * @OA\Property(
     *     property="page_num",
     *     type="integer",
     *     format="int32",
     *     description="The number of the page to retrieve"
     * )
     *
     * @OA\Property(
     *     property="page_size",
     *     type="integer",
     *     format="int32",
     *     description="The size of the page (number of items per page)"
     * )
     */
    public function rules(): array
    {
        return [
            'searchTerm' => 'required|string',
            'page_num' => 'integer',
            'page_size' => 'integer',
        ];
    }
}
