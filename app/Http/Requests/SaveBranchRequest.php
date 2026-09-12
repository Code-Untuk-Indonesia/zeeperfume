<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_cabang' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
            'jam_buka' => ['nullable', 'date_format:H:i,H:i:s'],
            'jam_tutup' => ['nullable', 'date_format:H:i,H:i:s'],
        ];
    }
}
