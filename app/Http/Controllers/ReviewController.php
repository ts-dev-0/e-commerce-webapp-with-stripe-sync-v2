<?php

namespace App\Http\Controllers;

use App\Actions\User\Review\CreateReview;
use App\Actions\User\Review\DeleteReview;
use App\Actions\User\Review\GetUserReviews;
use App\Actions\User\Review\UpdateReview;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index(Request $request, GetUserReviews $action)
    {
        $data = $action->handle($request->user());

        return Inertia::render('reviews', [
            'data' => $data,
        ]);
    }

    public function store(CreateReview $action)
    {
        $action->handle($user, $product, $rating, $comment);

        return redirect()
            ->back()
            ->with('success', 'Review posted.');
    }

    public function update(UpdateReview $action)
    {
        $action->handle($user, $review, $data);

        return redirect()
            ->back()
            ->with('success', 'Review updated.');
    }

    public function destroy(DeleteReview $action)
    {
        $action->handle($user, $review);

        return redirect()
            ->back()
            ->with('success', 'Review deleted.');
    }
}
