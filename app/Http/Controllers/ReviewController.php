<?php

namespace App\Http\Controllers;

use App\Actions\Review\CreateReview;
use App\Actions\Review\DeleteReview;
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

            return redirect()
                ->back()
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
        // TODO:更新の権限検証
        $review->update($request->validated());

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
