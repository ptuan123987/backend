<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        return true; // Change as per your authorization logic
    }

    // Get the validation rules that apply to the request.
    public function rules()
    {
        return [
            'parent_category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
        ];
    }
}
