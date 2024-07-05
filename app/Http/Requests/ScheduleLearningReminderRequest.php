<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleLearningReminderRequest extends FormRequest
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
            'schedules' => 'required|array',
            'schedules.*.time' => 'required|date_format:H:i',
            'schedules.*.frequency' => 'required|in:daily,weekly,monthly',
            'schedules.*.days' => 'nullable|array',
            'schedules.*.days.*' => 'integer|between:0,6',
            'schedules.*.message' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'schedules.*.time.required' => 'The time field is required.',
            'schedules.*.time.date_format' => 'The time field must be in the format HH:MM.',
            'schedules.*.frequency.required' => 'The frequency field is required.',
            'schedules.*.frequency.in' => 'The frequency must be one of daily, weekly, or monthly.',
            'schedules.*.days.array' => 'The days field must be an array.',
            'schedules.*.days.*.integer' => 'Each day must be an integer.',
            'schedules.*.days.*.between' => 'Each day must be between 0 and 6.',
            'schedules.*.message.required' => 'The message field is required.',
            'schedules.*.message.string' => 'The message field must be a string.',
            'schedules.*.message.max' => 'The message field must not exceed 255 characters.',
        ];
    }
}
