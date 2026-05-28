<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientRequest extends FormRequest
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
            'emoji' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:ingredients,slug'],
            'category' => ['required', 'string', 'in:sayuran,daging,bumbu,karbohidrat,seafood,susu,buah,lainnya'],
            'default_unit' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'in:0,1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'emoji.required' => 'Emoji bahan wajib dipilih.',
            'name.required' => 'Nama bahan wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan.',
            'category.required' => 'Kategori bahan wajib ditentukan.',
            'category.in' => 'Kategori yang dipilih tidak valid.',
            'default_unit.required' => 'Satuan default wajib diisi.',
            'is_active.in' => 'Status keaktifan harus berupa 0 atau 1.',
        ];
    }
}
