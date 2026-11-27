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
            'criteria' => ['nullable', 'array'],
            'criteria.*' => ['required', 'integer', 'distinct', Rule::exists('criteria', 'id')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Nama beasiswa wajib diisi.',
            'title.string' => 'Nama beasiswa harus berupa teks.',
            'title.max' => 'Nama beasiswa maksimal :max karakter.',
            'slug.required' => 'Slug URL wajib diisi.',
            'slug.string' => 'Slug URL harus berupa teks.',
            'slug.max' => 'Slug URL maksimal :max karakter.',
            'slug.unique' => 'Slug URL sudah digunakan.',
            'provider.required' => 'Penyedia beasiswa wajib diisi.',
            'provider.string' => 'Penyedia beasiswa harus berupa teks.',
            'provider.max' => 'Nama penyedia maksimal :max karakter.',
            'country.required' => 'Negara beasiswa wajib diisi.',
            'country.string' => 'Negara beasiswa harus berupa teks.',
            'country.max' => 'Negara beasiswa maksimal :max karakter.',
            'degree_level.required' => 'Jenjang beasiswa wajib dipilih.',
            'degree_level.in' => 'Jenjang yang dipilih tidak valid.',
            'funding_type.required' => 'Jenis pendanaan wajib dipilih.',
            'funding_type.in' => 'Jenis pendanaan yang dipilih tidak valid.',
            'language_test.required' => 'Persyaratan tes bahasa wajib dipilih.',
            'language_test.in' => 'Persyaratan tes bahasa yang dipilih tidak valid.',
            'return_obligation.required' => 'Pilihan kewajiban kembali wajib dipilih.',
            'return_obligation.boolean' => 'Pilihan kewajiban kembali tidak valid.',
            'return_duration_years.integer' => 'Durasi kewajiban kembali harus berupa bilangan bulat.',
            'return_duration_years.min' => 'Durasi kewajiban kembali minimal :min tahun.',
            'return_duration_years.max' => 'Durasi kewajiban kembali maksimal :max tahun.',
            'min_gpa.numeric' => 'IPK minimum harus berupa angka.',
            'min_gpa.between' => 'IPK minimum harus berada di antara 0 dan 4.',
            'max_age.integer' => 'Batas usia harus berupa bilangan bulat.',
            'max_age.between' => 'Batas usia harus berada di antara 16 dan 100 tahun.',
            'open_date.date' => 'Tanggal pembukaan tidak valid.',
            'deadline.required' => 'Deadline beasiswa wajib diisi.',
            'deadline.date' => 'Tanggal deadline tidak valid.',
            'official_url.required' => 'URL resmi wajib diisi.',
            'official_url.url' => 'URL resmi harus menggunakan alamat HTTP atau HTTPS yang valid.',
            'official_url.max' => 'URL resmi maksimal :max karakter.',
            'description.required' => 'Deskripsi beasiswa wajib diisi.',
            'description.string' => 'Deskripsi beasiswa harus berupa teks.',
            'description.max' => 'Deskripsi beasiswa maksimal :max karakter.',
            'is_active.required' => 'Status visibilitas beasiswa wajib dipilih.',
            'is_active.boolean' => 'Status visibilitas beasiswa tidak valid.',
            'criteria.array' => 'Daftar kriteria tambahan tidak valid.',
            'criteria.*.required' => 'Setiap kriteria tambahan harus dipilih.',
            'criteria.*.integer' => 'Kriteria tambahan yang dipilih tidak valid.',
            'criteria.*.distinct' => 'Kriteria tambahan tidak boleh dipilih lebih dari sekali.',
            'criteria.*.exists' => 'Salah satu kriteria tambahan tidak tersedia.',
        ];
    }
}
