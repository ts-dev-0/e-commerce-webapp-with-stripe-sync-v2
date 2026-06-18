<?php

namespace App\Http\Controllers;

use App\Actions\Review\CreateReview;
use App\Http\Requests\Review\DestroyReviewRequest;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, CreateReview $action)
    {
        try {
            $validatedData = $request->validated();

            $action->handle(
                $request->user(),
                $validatedData,
            );

            return back()
                ->with('success', 'Review posted.');
        } catch (\App\Exceptions\ReviewAlreadyExistsException $e) {
            error_log($e->getMessage());

            throw ValidationException::withMessages([
                // TODO: Change the key to 'id' once the front-end is fixed.
                'comment' => 'You have already reviewed this product.',
            ]);
        }
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
