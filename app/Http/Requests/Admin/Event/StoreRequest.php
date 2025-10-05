<?php

namespace App\Http\Requests\Admin\Event;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

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
        $rules = Event::getBaseRules();

        $rules += [
            'thumbnail' => ['nullable', 'string'],
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'イベント名は必須です。',
            'discount_type.required' => '割引タイプを選択してください。',
            'start_date.required' => '開始日時は必須です。',
            'end_date.required' => '終了日時は必須です。',
            'end_date.after' => '終了日時は開始日時より後の日時を設定してください。',
        ];
    }
}
