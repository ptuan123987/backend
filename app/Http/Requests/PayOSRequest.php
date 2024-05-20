<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayOSRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_ids' => 'required|array',
            'payment_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'course_ids.required' => 'The course IDs are required.',
            'course_ids.array' => 'The course IDs must be provided as an array.',
            'payment_type.required' => 'The payment type is required.',
            'payment_type.string' => 'The payment type must be a string.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.',
        ];
    }
}

