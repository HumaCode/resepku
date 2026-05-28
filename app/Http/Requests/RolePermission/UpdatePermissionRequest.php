<?php

namespace App\Http\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
        $permissionId = $this->route('permission') instanceof \App\Models\Permission 
            ? $this->route('permission')->id 
            : $this->route('permission');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $permissionId],
            'guard_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'in:0,1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama permission wajib diisi.',
            'name.unique' => 'Nama permission sudah digunakan.',
            'is_active.in' => 'Status keaktifan harus berupa 0 atau 1.',
        ];
    }
}
