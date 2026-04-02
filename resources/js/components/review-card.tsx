import { useModalStore } from '@/stores/modalStore';
import { Review } from '@/types/review';
import { Pencil, Trash2 } from 'lucide-react';
import { useState } from 'react';
import EditReviewForm from './edit-review-form';
import { Button } from './ui/button';

interface ReviewCardProps {
    review: Review;
    isOwnReview: boolean;
}

export default function ReviewCard({ review, isOwnReview }: ReviewCardProps) {
    const [editedMode, setEditedMode] = useState<boolean>(false);

    const openModal = useModalStore((state) => state.openModal);

    return (
        <div key={review.id} className="rounded-md border border-slate-200 p-4">
            <div className="flex items-center justify-between">
                <div className="text-sm font-medium text-slate-800">
                    {review.user.name}
                </div>

                <div className="flex items-center gap-3">
                    <div className="text-xs text-slate-400">
                        {review.createdAt}
                        {review.isEdited && '（編集済み）'}
                    </div>
                    {isOwnReview && (
                        <div className="flex items-center gap-2">
                            <Button
                                size={'icon'}
                                variant={'ghost'}
                                className="text-slate-400 transition-colors duration-150 hover:text-slate-600"
                                onClick={() => setEditedMode(!editedMode)}
                            >
                                <Pencil className="size-4 transition-colors duration-150" />
                            </Button>

                            <Button
                                size={'icon'}
                                variant={'ghost'}
                                className="text-slate-400 transition-colors duration-150 hover:bg-red-200 hover:text-red-500"
                                onClick={() =>
                                    openModal('deleteReviewConfirm', {
                                        id: review.id,
                                    })
                                }
                            >
                                <Trash2 className="size-4 transition-colors duration-150" />
                            </Button>
                        </div>
                    )}
                </div>
            </div>

            {!editedMode ? (
                <>
                    <div className="mt-1 text-sm text-amber-400">
                        {'★'.repeat(review.rating)}
                    </div>

                    <p className="mt-2 text-sm text-slate-600">
                        {review.comment}
                    </p>
                </>
            ) : (
                <EditReviewForm
                    reviewId={review.id}
                    rating={review.rating}
                    comment={review.comment}
                    setEditMode={setEditedMode}
                />
            )}
        </div>
    );
}
