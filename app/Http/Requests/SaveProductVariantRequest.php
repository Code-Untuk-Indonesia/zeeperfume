<?php

namespace App\Http\Requests;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variant = $this->route('productVariant');
        $variant = $variant instanceof ProductVariant ? $variant : null;

        return [
            'produk_id' => $variant === null
                ? [
                    'required',
                    'integer',
                    Rule::exists('products', 'id')->whereNull('deleted_at'),
                ]
                : ['required', 'integer', Rule::in([$variant->produk_id])],
            'sku' => [
                'required',
                'string',
                'max:80',
                Rule::unique('produk_varian', 'sku')->ignore($variant?->getKey()),
            ],
            'nama_varian' => ['required', 'string', 'max:100'],
            'harga_beli' => ['sometimes', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
        ];
    }
}
