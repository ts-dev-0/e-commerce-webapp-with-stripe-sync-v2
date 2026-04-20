<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCheckoutRequest extends FormRequest
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
            'address_id' => [
                'required',
                'integer',
                Rule::exists('addresses', 'id')->where(
                    fn ($query) => $query->where('user_id', auth()->id())
                ),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.required' => '住所の指定は必須です。',
            'address_id.integer' => '住所の指定が不正です。',
            'address_id.exists' => '指定された住所が存在しないか、利用できません。',
        ];
    }
}
