<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class FavoriteController extends Controller
{
    public function index()
    {
        return Inertia::render('favorite-products');
    }

    public function store()
    {
        return redirect()->back()->with('success', 'Added to favorite.');
    }

    public function destroy()
    {
        return redirect()->back()->with('success', 'Removed favorite.');
    }
}
