<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cabang_id' => [
                'required',
                'integer',
                Rule::exists('branches', 'id')->whereNull('deleted_at'),
            ],
            'varian_id' => [
                'required',
                'integer',
                Rule::exists('produk_varian', 'id')->whereNull('deleted_at'),
            ],
            'jenis_riwayat' => [
                'required',
                Rule::in(['masuk', 'keluar', 'rusak', 'penyesuaian']),
            ],
            'stok' => ['required', 'integer', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
