<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('address'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string'],
            'postal_code' => ['required', 'digits:7'],
            'prefecture' => [
                'required',
                'string',
                Rule::in([
                    '北海道',
                    '青森県',
                    '岩手県',
                    '宮城県',
                    '秋田県',
                    '山形県',
                    '福島県',
                    '茨城県',
                    '栃木県',
                    '群馬県',
                    '埼玉県',
                    '千葉県',
                    '東京都',
                    '神奈川県',
                    '新潟県',
                    '富山県',
                    '石川県',
                    '福井県',
                    '山梨県',
                    '長野県',
                    '岐阜県',
                    '静岡県',
                    '愛知県',
                    '三重県',
                    '滋賀県',
                    '京都府',
                    '大阪府',
                    '兵庫県',
                    '奈良県',
                    '和歌山県',
                    '鳥取県',
                    '島根県',
                    '岡山県',
                    '広島県',
                    '山口県',
                    '徳島県',
                    '香川県',
                    '愛媛県',
                    '高知県',
                    '福岡県',
                    '佐賀県',
                    '長崎県',
                    '熊本県',
                    '大分県',
                    '宮崎県',
                    '鹿児島県',
                    '沖縄県',
                ]),
            ],
            'city' => ['required', 'string', 'max:50'],
            'address_line' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'digits_between:10,11'],
            'is_default' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => '氏名は必須です。',
            'full_name.string' => '氏名は文字列で入力してください。',

            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.digits' => '郵便番号は7桁の数字で入力してください。',

            'prefecture.required' => '都道府県は必須です。',
            'prefecture.string' => '都道府県は文字列で入力してください。',
            'prefecture.in' => '選択された都道府県が不正です。',

            'city.required' => '市区町村は必須です。',
            'city.string' => '市区町村は文字列で入力してください。',
            'city.max' => '市区町村は50文字以内で入力してください。',

            'address_line.required' => '番地・建物名は必須です。',
            'address_line.string' => '番地・建物名は文字列で入力してください。',
            'address_line.max' => '番地・建物名は100文字以内で入力してください。',

            'phone_number.required' => '電話番号は必須です。',
            'phone_number.digits_between' => '電話番号は10桁または11桁の数字で入力してください。',

            'is_default.required' => 'デフォルト住所の指定は必須です。',
            'is_default.boolean' => 'デフォルト住所の指定が不正です。',
        ];
    }
}
