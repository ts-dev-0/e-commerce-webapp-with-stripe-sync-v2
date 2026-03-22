<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'postal_code' => fake()->numerify('#######'),
            'prefecture' => fake()->randomElement([
                '北海道',
                '青森県','岩手県','宮城県','秋田県','山形県','福島県',
                '茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県',
                '新潟県','富山県','石川県','福井県','山梨県','長野県',
                '岐阜県','静岡県','愛知県','三重県',
                '滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県',
                '鳥取県','島根県','岡山県','広島県','山口県',
                '徳島県','香川県','愛媛県','高知県',
                '福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県',
                '沖縄県',
            ]),
            'city' => fake()->city(),
            'address_line' => fake()->streetAddress(),
            'phone_number' => fake()->numerify('0##########'),
            'is_default' => false,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
