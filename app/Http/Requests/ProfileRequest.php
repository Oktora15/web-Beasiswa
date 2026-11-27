<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'student';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'gpa' => ['nullable', 'numeric', 'between:0,4'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'ielts_score' => ['nullable', 'numeric', 'between:0,9'],
            'toefl_score' => ['nullable', 'integer', 'between:0,120'],
        ];
    }
}
