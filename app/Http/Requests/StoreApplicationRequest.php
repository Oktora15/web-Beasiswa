<?php

namespace App\Http\Requests;

use App\ApplicationStatus;
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
        $draftApplication = $scholarship ? $this->user()->applications()
            ->where('scholarship_id', $scholarship->id)
            ->where('status', ApplicationStatus::Draft->value)
            ->with('documents:id,application_id,document_type')
            ->first() : null;
        $existingDocumentTypes = $draftApplication?->documents
            ->pluck('document_type')
            ->all() ?? [];
        $requiredDocumentRule = fn (string $documentType): string => $isSubmitting
            && $documentType !== 'language_certificate'
            && ! in_array($documentType, $existingDocumentTypes, true)
                ? 'required'
                : 'nullable';

        return [
            'intent' => ['required', 'in:draft,submit'],
            'motivation' => ['nullable', 'string', 'max:5000'],
            'cv' => [$requiredDocumentRule('cv'), 'file', 'mimes:pdf', 'extensions:pdf', 'max:2048'],
            'transcript' => [$requiredDocumentRule('transcript'), 'file', 'mimes:pdf', 'extensions:pdf', 'max:2048'],
            'motivation_letter' => [$requiredDocumentRule('motivation_letter'), 'file', 'mimes:pdf', 'extensions:pdf', 'max:2048'],
            'language_certificate' => [$requiredDocumentRule('language_certificate'), 'file', 'mimes:pdf', 'extensions:pdf', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [
            'cv.required' => 'Unggah CV PDF sebelum mengirim lamaran.',
            'transcript.required' => 'Unggah transkrip PDF sebelum mengirim lamaran.',
            'motivation_letter.required' => 'Unggah surat motivasi PDF sebelum mengirim lamaran.',
        ];

        foreach ($this->attributes() as $field => $label) {
            $messages["{$field}.file"] = "Unggah {$label} sebagai file.";
            $messages["{$field}.mimes"] = "{$label} harus berformat PDF.";
            $messages["{$field}.extensions"] = "Ekstensi {$label} harus .pdf.";
            $messages["{$field}.max"] = "Ukuran {$label} maksimal 2 MB.";
        }

        return $messages;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'cv' => 'CV',
            'transcript' => 'transkrip',
            'motivation_letter' => 'surat motivasi',
            'language_certificate' => 'sertifikat bahasa',
        ];
    }
}
