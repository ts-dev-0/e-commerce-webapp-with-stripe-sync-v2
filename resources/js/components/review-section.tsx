import { useModalStore } from '@/stores/modalStore';
import { SharedData } from '@/types';
import { Review } from '@/types/review';
import { usePage } from '@inertiajs/react';
import { Pencil, Trash2 } from 'lucide-react';
import ReviewForm from './review-form';
import { Button } from './ui/button';

interface ReviewSectionProps {
    productId: number;
    reviews: Review[];
    averageRating: number;
}

export default function ReviewSection({
    productId,
    reviews,
    averageRating,
}: ReviewSectionProps) {
    const { auth } = usePage<SharedData>().props;

    const openModal = useModalStore((state) => state.openModal);

    return (
        <section className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-slate-800">レビュー</h2>
            {auth.user && <ReviewForm productId={productId} />}

            <div className="mt-6 flex items-center gap-4">
                <div className="text-2xl font-bold text-slate-800">
                    平均評価：{averageRating} / 5
                </div>

                <div className="text-sm text-slate-500">
                    ({reviews.length}件のレビュー)
                </div>
            </div>

            <div className="mt-4 space-y-4">
                {reviews.length > 0 ? (
                    reviews.map((review) => (
                        <div
                            key={review.id}
                            className="rounded-md border border-slate-200 p-4"
                        >
                            <div className="flex items-center justify-between">
                                <div className="text-sm font-medium text-slate-800">
                                    {review.user.name}
                                </div>

                                <div className="flex items-center gap-3">
                                    <div className="text-xs text-slate-400">
                                        {review.createdAt}
                                        {review.isEdited && '（編集済み）'}
                                    </div>
                                    {auth.user &&
                                        review.userId === auth.user.id && (
                                            <div className="flex items-center gap-2">
                                                <Button
                                                    size={'icon'}
                                                    variant={'ghost'}
                                                    className="text-slate-400 transition-colors duration-150 hover:text-slate-600"
                                                >
                                                    <Pencil className="size-4 transition-colors duration-150" />
                                                </Button>

                                                <Button
                                                    size={'icon'}
                                                    variant={'ghost'}
                                                    className="text-slate-400 transition-colors duration-150 hover:bg-red-200 hover:text-red-500"
                                                    onClick={() =>
                                                        openModal(
                                                            'deleteReviewConfirm',
                                                            { id: review.id },
                                                        )
                                                    }
                                                >
                                                    <Trash2 className="size-4 transition-colors duration-150" />
                                                </Button>
                                            </div>
                                        )}
                                </div>
                            </div>

                            <div className="mt-1 text-sm text-amber-400">
                                {'★'.repeat(review.rating)}
                            </div>

                            <p className="mt-2 text-sm text-slate-600">
                                {review.comment}
                            </p>
                        </div>
                    ))
                ) : (
                    <div className="rounded-md border border-slate-200 bg-slate-50 p-4 text-center text-sm text-slate-500">
                        まだレビューがありません。
                    </div>
                )}
            </div>
        </section>
    );
}
