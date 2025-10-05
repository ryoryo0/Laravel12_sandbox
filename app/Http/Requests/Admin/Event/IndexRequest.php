<?php

namespace App\Http\Requests\Admin\Event;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'name'          => ['nullable', 'string', 'max:255'],
            'is_active'     => ['nullable', 'boolean'],
            'discount_type' => ['nullable', 'string', 'in:rate,amount'],
        ];

        return $rules;
    }
}
