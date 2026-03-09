<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Product\CreateProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\CreateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        //
    }

    public function store(CreateProductRequest $request, CreateProduct $action)
    {
        $validatedData = $request->validated();

        $action->handle($validatedData);

        return redirect()
            ->route('admin.products.create')
            ->with('success', 'Product created.');
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
