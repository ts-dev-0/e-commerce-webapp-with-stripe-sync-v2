<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Search\SearchAllProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Search\SearchAllProductRequest;
use Inertia\Inertia;

class SearchAllProductController extends Controller
{
    public function __invoke(SearchAllProductRequest $request, SearchAllProduct $action)
    {
        $validatedData = $request->validated();

        $products = $action->handle($validatedData['keyword']);

        return Inertia::render('admin/search/all-products', [
            'data' => $products,
        ]);
    }
}
