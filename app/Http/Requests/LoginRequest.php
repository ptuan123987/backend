<?php

namespace App\Http\Requests;

use App\Rules\ProvinceDistricRule;
use App\Rules\TuNguVanHoa;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Login Request",
 *     description="Login request body data",
 *     type="object",
 *     required={"email", "password"}
 * )
 */
class LoginRequest extends FormRequest
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
            new TuNguVanHoa(),
            new ProvinceDistricRule($this->province),
            "password" => "required|min:8|regex:/^(?=.*[A-Z])(?=.*\d).+$/",
        ];
    }
   /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [ 'required' => "Truong :attributes meo dung dau ban ei",
        'password.min' =>'password qua ngan'];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => 'Tai khoan email'
        ];
    }
}
