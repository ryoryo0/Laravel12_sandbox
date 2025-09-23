<?php

namespace App\Http\Requests\Admin\Product;

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
        $rules = [
            'name'         => ['required','string', 'max:255'],
            'description'  => ['required','string', 'max:255'],
            'category_ids' => ['required','array', Rule::exists('categories', 'id')],
            'code'         => ['required','string', 'max:255', Rule::unique('products', 'code')],
            'detail_json'  => ['nullable','json',],
            'is_public'    => ['required','boolean'],
            'is_pick_up'   => ['required','boolean'],
        ];

        $rules += [
            'thumbnail'       => ['nullable', 'string'],
            'other_thumbnail' => ['nullable', 'array'],
        ];

        return $rules;
    }
}
