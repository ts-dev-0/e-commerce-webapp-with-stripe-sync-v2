import ReviewForm from './review-form';

interface ReviewSectionProps {
  productId: number;
}

export default function ReviewSection({productId}: ReviewSectionProps) {
    const reviews = [
        {
            id: 1,
            name: '山田 太郎',
            rating: 5,
            comment: 'とても良い商品でした。',
            date: '2025-03-01',
        },
        {
            id: 2,
            name: '佐藤 花子',
            rating: 4,
            comment: '配送も早く満足です。',
            date: '2025-03-02',
        },
    ];

    const averageRating = 4.5;

    return (
        <section className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-slate-800">レビュー</h2>

            <ReviewForm productId={productId}/>

            <div className="mt-6 flex items-center gap-4">
                <div className="text-2xl font-bold text-slate-800">
                    {averageRating}
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
                                    {review.name}
                                </div>

                                <div className="text-xs text-slate-400">
                                    {review.date}
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
