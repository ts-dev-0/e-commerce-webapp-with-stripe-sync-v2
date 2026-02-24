<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        $productIds = DB::table('products')->pluck('id')->toArray();

        $data = [];
        foreach ($productIds as $productId) {
            $data[] = [
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'product_id'  => $productId
            ];
        }

        DB::table('category_product')->insertOrIgnore($data);
    }
}
