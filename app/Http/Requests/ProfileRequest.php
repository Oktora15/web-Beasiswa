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
            'country_of_origin' => ['nullable', 'string', 'max:100'],
            'ielts_score' => ['nullable', 'numeric', 'between:0,9', 'multiple_of:0.5'],
            'toefl_score' => ['nullable', 'integer', 'between:0,120'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'gpa.numeric' => 'IPK harus berupa angka.',
            'gpa.between' => 'IPK harus berada di antara 0 dan 4.',
            'birth_date.date' => 'Tanggal lahir tidak valid.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'country_of_origin.max' => 'Negara asal maksimal 100 karakter.',
            'ielts_score.numeric' => 'Skor IELTS harus berupa angka.',
            'ielts_score.between' => 'Skor IELTS harus berada di antara 0 dan 9.',
            'ielts_score.multiple_of' => 'Skor IELTS harus menggunakan kelipatan 0,5.',
            'toefl_score.integer' => 'Skor TOEFL harus berupa bilangan bulat.',
            'toefl_score.between' => 'Skor TOEFL harus berada di antara 0 dan 120.',
        ];
    }
}
