<?php

namespace App\Http\Controllers;

use App\Actions\Review\CreateReview;
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
            $validatedData,
        );

        return back()
            ->with('success', 'Review posted.');
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->update($request->validated());

        return back()
            ->with('success', 'Review updated.');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->query()->delete();

        return back()
            ->with('success', 'Review deleted.');
    }
}
