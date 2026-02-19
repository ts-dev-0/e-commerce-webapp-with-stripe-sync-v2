<?php

namespace App\Actions\Admin\Category;

use App\Models\Category;

class UpdateCategory
{
    public function handle(Category $category, array $data): Category
    {
        $category->update([
            'name' => $data['name'],
        ]);

        return $category->refresh();
    }
}
