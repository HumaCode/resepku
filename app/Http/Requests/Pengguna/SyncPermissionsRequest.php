<?php

namespace App\Http\Requests\Pengguna;

use Illuminate\Foundation\Http\FormRequest;

class SyncPermissionsRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'matrix' => ['present', 'array'],
            'matrix.*' => ['nullable', 'array'],
            'matrix.*.*' => ['string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'matrix.present' => 'Matriks izin wajib dikirimkan.',
            'matrix.array' => 'Format matriks izin tidak valid.',
        ];
    }
}
