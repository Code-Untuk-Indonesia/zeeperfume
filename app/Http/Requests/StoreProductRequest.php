<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->whereNull('deleted_at'),
            ],
            'nama_produk' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tipe_stok' => ['required', Rule::in(['ada_stok', 'tanpa_stok'])],
            'image' => ['nullable', 'string', 'max:255'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.sku' => [
                'required',
                'string',
                'max:80',
                'distinct',
                Rule::unique('produk_varian', 'sku'),
            ],
            'variants.*.nama_varian' => ['required', 'string', 'max:100'],
            'variants.*.harga_beli' => ['sometimes', 'numeric', 'min:0'],
            'variants.*.harga_jual' => ['required', 'numeric', 'min:0'],
            'variants.*.image' => ['nullable', 'string', 'max:255'],
            'variants.*.satuan' => ['required', 'string', 'max:50'],
        ];
    }
}
