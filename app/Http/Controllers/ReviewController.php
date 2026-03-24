<?php

namespace App\Http\Controllers;

use App\Actions\Review\CreateReview;
use App\Actions\Review\DeleteReview;
use App\Actions\Review\GetUserReviews;
use App\Actions\Review\UpdateReview;
use App\Http\Requests\User\Review\CreateReviewRequest;
use App\Http\Requests\User\Review\DeleteReviewRequest;
use App\Http\Requests\User\Review\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
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

    public function store(CreateReviewRequest $request, CreateReview $action)
    {
        $validatedData = $request->validated();

        $action->handle(
            $request->user(),
            $validatedData['product_id'],
            $validatedData['rating'],
            $validatedData['comment'],
        );

        return redirect()
            ->back()
            ->with('success', 'Review posted.');
    }

    public function update(UpdateReviewRequest $request, UpdateReview $action, Review $review)
    {
        $action->handle(
            $review, 
            $request->validated(),
        );

        return redirect()
            ->back()
            ->with('success', 'Review updated.');
    }

    public function destroy(DeleteReviewRequest $request, DeleteReview $action, Review $review)
    {
        $action->handle($review);

        return redirect()
            ->back()
            ->with('success', 'Review deleted.');
    }
}
