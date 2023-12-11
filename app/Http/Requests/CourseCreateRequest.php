<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You need to change this to `true` to allow users to send this request,
        // or implement your own logic to determine if a user is authorized.
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
            'video' => [
                'required',       
                'file',
                'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4', 
                'max:204800'
            ],
        ];
    }
}
