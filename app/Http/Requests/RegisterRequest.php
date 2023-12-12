<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="User Registration Request",
 *     description="User registration request body data",
 *     type="object",
 *     required={"display_name", "email", "password"}
 * )
 */
class RegisterRequest extends FormRequest
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
            "display_name" => "required|max:100|string",
            "email"=> "required|email|unique:users|max:255",
            "password" => "required|min:8|regex:/^(?=.*[A-Z])(?=.*\d).+$/"
        ];
    }
}
