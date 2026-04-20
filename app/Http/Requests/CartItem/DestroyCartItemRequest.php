<?php

namespace App\Http\Requests\CartItem;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCartItemRequest extends FormRequest
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
            'product_id' => ['bail', 'required', 'integer', 'exists:products,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => '商品IDは必須です。',
            'product_id.integer' => '商品IDは整数で入力してください。',
            'product_id.exists' => '指定された商品が存在しません。',
        ];
    }
}
