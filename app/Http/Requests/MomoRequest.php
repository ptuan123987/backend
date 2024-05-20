<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MomoRequest extends FormRequest
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
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0',
            'course_ids' => 'required|array',
            'payment_type' => [
                'required',
                Rule::in(['cash', 'credit_card', 'momo', 'paypal']),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.',
            'course_ids.required' => 'At least one course must be selected.',
            'course_ids.array' => 'The course IDs must be provided as an array.',
            'payment_type.required' => 'The payment type is required.',
            'payment_type.in' => 'The payment type must be either cash, credit_card, momo, or paypal.',
        ];
    }
}
