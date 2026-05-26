<?php

namespace App\Http\Controllers;

use App\Actions\Review\CreateReview;
use App\Actions\Review\DeleteReview;
use App\Actions\Review\UpdateReview;
use App\Http\Requests\Review\DestroyReviewRequest;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, CreateReview $action)
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

    public function destroy(DestroyReviewRequest $request, DeleteReview $action, Review $review)
    {
        $action->handle($review);

        return redirect()
            ->back()
            ->with('success', 'Review deleted.');
    }
}
