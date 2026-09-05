<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveEmployeeRequest extends FormRequest
{
    private const EMPLOYEE_ROLES = ['admin', 'kasir'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee');

        return [
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'username' => [
                'required',
                'string',
                'max:80',
                Rule::unique('users', 'username')->ignore($employeeId),
            ],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'string',
                'min:8',
            ],
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')->whereIn('nama_role', self::EMPLOYEE_ROLES),
            ],
            'cabang_id' => [
                'nullable',
                'integer',
                Rule::exists('branches', 'id')->whereNull('deleted_at'),
            ],
            'status_aktif' => ['sometimes', 'boolean'],
        ];
    }
}
