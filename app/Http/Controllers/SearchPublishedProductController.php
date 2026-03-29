<?php

namespace App\Http\Controllers;

use App\Actions\Search\SearchPublishedProduct;
use App\Http\Requests\User\Search\SearchPublishedProductRequest;
use Inertia\Inertia;

class SearchPublishedProductController extends Controller
{
    public function __invoke(SearchPublishedProductRequest $request, SearchPublishedProduct $action)
    {
        $validatedData = $request->validated();
        $data = $action->handle($validatedData['keyword']);

        return Inertia::render('search-product', [
            'data' => $data,
            'keyword'=> $validatedData['keyword'],
        ]);
    }
}
