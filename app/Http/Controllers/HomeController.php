<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    private const Limit = 15;

    public function index()
    {
        $newArrivalProducts = Product::newArrivals(self::Limit)->get();

        return Inertia::render('home', [
            'home' => ProductResource::collection($newArrivalProducts),
        ]);
    }
}
