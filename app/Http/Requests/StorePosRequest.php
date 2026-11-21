<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'kasir'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array:product_id,qty'],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('products', 'id')->whereNull('deleted_at'),
            ],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'pay' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Pilih minimal satu produk.',
            'items.*.product_id.exists' => 'Salah satu produk tidak tersedia.',
            'items.*.product_id.distinct' => 'Produk yang sama hanya boleh dimasukkan satu kali.',
            'items.*.qty.min' => 'Jumlah produk minimal satu.',
            'pay.required' => 'Masukkan nominal pembayaran.',
        ];
    }
}
