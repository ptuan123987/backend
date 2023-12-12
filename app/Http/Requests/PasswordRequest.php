<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Password Change Request",
 *     description="Password change request body data",
 *     type="object",
 *     required={"old_password", "new_password"}
 * )
 */
class PasswordRequest extends FormRequest
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
            'old_password' => "required|min:8|regex:/^(?=.*[A-Z])(?=.*\d).+$/",
            'new_password' => "required|min:8|regex:/^(?=.*[A-Z])(?=.*\d).+$/",
        ];
    }
}
