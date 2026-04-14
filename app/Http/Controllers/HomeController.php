<?php

namespace App\Http\Controllers;

use App\Actions\Home\HomeIndex;
use App\Http\Resources\ProductResource;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(HomeIndex $action)
    {
        $home = $action->handle();

        return Inertia::render('home', [
            'home' => ProductResource::collection($home),
        ]);
    }
}
