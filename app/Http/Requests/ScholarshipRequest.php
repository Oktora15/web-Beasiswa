<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScholarshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $scholarship = $this->route('scholarship');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('scholarships', 'slug')->ignore($scholarship)],
            'provider' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'degree_level' => ['required', Rule::in(['S1', 'S2', 'S3'])],
            'funding_type' => ['required', Rule::in(['fully', 'partial'])],
            'language_test' => ['required', Rule::in(['none', 'ielts', 'toefl', 'ielts_or_toefl', 'waiver'])],
            'return_obligation' => ['required', 'boolean'],
            'return_duration_years' => ['nullable', 'integer', 'min:1', 'max:99'],
            'min_gpa' => ['nullable', 'numeric', 'between:0,4'],
            'max_age' => ['nullable', 'integer', 'between:16,100'],
            'open_date' => ['nullable', 'date'],
            'deadline' => ['required', 'date'],
            'official_url' => ['required', 'url:http,https', 'max:2048'],
            'description' => ['required', 'string', 'max:10000'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
