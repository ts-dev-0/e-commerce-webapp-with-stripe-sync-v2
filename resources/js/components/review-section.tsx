import { SharedData } from '@/types';
import { Review } from '@/types/review';
import { usePage } from '@inertiajs/react';
import ReviewCard from './review-card';
import ReviewForm from './review-form';

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
                        <ReviewCard
                            review={review}
                            isOwnReview={
                                auth.user && auth.user.id === review.userId
                            }
                        />
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
