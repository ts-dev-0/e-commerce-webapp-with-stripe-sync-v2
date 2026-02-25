<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
    {
        // TODO: Get review data process

        // TODO: Add review data
        return Inertia::render('reviews');
    }

    public function store()
    {
        // TODO: Save process

        return redirect()
            ->back()
            ->with('success', 'Review posted.');
    }

    public function update()
    {
        // TODO: Update process

        return redirect()
            ->back()
            ->with('success', 'Review updated.');
    }

    public function destroy()
    {
        // TODO: Delete process

        return redirect()
            ->back()
            ->with('success', 'Review deleted.');
    }
}
