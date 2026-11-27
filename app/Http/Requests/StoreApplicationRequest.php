<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $isSubmitting = $this->input('intent') === 'submit';
        $scholarship = $this->route('scholarship');
        $draftHasDocuments = $scholarship && $this->user()->applications()
            ->where('scholarship_id', $scholarship->id)
            ->where('status', 'draft')
            ->whereHas('documents')
            ->exists();
        $requiresDocuments = $isSubmitting && ! $draftHasDocuments;

        return [
            'intent' => ['required', 'in:draft,submit'],
            'motivation' => $isSubmitting
                ? ['required', 'string', 'min:50', 'max:5000']
                : ['nullable', 'string', 'max:5000'],
            'documents' => $requiresDocuments
                ? ['required', 'array', 'min:1', 'max:8']
                : ['nullable', 'array', 'max:8'],
            'documents.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
