<?php

namespace App\Http\Requests\Admin\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.color' => ['required', 'string', 'max:255'],
            'variants.*.size' => ['required', 'string', 'max:255'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'variants.required' => '少なくとも1つのバリエーションを登録してください。',
            'variants.*.color.required' => 'カラーは必須です。',
            'variants.*.size.required' => 'サイズは必須です。',
            'variants.*.stock.required' => '在庫数は必須です。',
            'variants.*.stock.min' => '在庫数は0以上で入力してください。',
            'variants.*.price.required' => '価格は必須です。',
            'variants.*.price.min' => '価格は0以上で入力してください。',
        ];
    }
}
