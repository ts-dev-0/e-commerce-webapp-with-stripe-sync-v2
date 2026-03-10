<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Product\CreateProduct;
use App\Actions\Admin\Product\DeleteProduct;
use App\Actions\Admin\Product\UpdateProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\CreateProductRequest;
use App\Http\Requests\Admin\Product\DeleteProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function create()
    {
        return Inertia::render('admin/product/create');
    }

    public function store(CreateProductRequest $request, CreateProduct $action)
    {
        $validatedData = $request->validated();

        $action->handle($validatedData);

        return redirect()
            ->route('admin.products.create')
            ->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return Inertia::render('admin/product/edit', [
            'data' => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, UpdateProduct $action, Product $product)
    {
        $validatedData = $request->validated();

        $updatedProduct = $action->handle($product, $validatedData);

        return redirect()
            ->route('admin.products.edit', $updatedProduct)
            ->with('success', 'Product updated.');
    }

    public function destroy(DeleteProductRequest $request, DeleteProduct $action, Product $product)
    {
        $action->handle($product);

        return redirect()
            ->route('admin.products.create')
            ->with('success', 'Product deleted.');
    }
}
