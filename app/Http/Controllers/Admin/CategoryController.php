<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Category\CreateCategory;
use App\Actions\Admin\Category\DeleteCategory;
use App\Actions\Admin\Category\UpdateCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CreateCategoryRequest;
use App\Http\Requests\Admin\Category\DeleteCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return Inertia::render('admin/category/index', [
            'data' => $categories,
        ]);
    }

    public function store(CreateCategoryRequest $request, CreateCategory $action)
    {
        $validatedData = $request->validated();

        $action->handle($validatedData);

        return redirect(route('admin.categories.index'))
            ->with('success', 'New category created.');
    }

    public function update(UpdateCategoryRequest $request, UpdateCategory $action, Category $category)
    {
        $validatedData = $request->validated();

        $action->handle($category, $validatedData);

        return redirect(route('admin.categories.index'))
            ->with('success', 'Category updated.');
    }

    public function destroy(DeleteCategoryRequest $request, DeleteCategory $action, Category $category)
    {
        $action->handle($category);

        return redirect(route('admin.categories.index'))
            ->with('success', 'Category deleted.');
    }
}
