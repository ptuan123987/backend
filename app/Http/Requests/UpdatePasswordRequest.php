<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdatePasswordRequest",
 *     type="object",
 *     title="Update Password Request",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", description="User email"),
 *     @OA\Property(property="password", type="string", description="User password (minimum 8 characters with at least one uppercase letter and one digit)")
 * )
 */
class UpdatePasswordRequest extends FormRequest
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
            "email" => "required|email",
            "password" => "required|min:8|regex:/^(?=.*[A-Z])(?=.*\d).+$/",
        ];
    }
}
