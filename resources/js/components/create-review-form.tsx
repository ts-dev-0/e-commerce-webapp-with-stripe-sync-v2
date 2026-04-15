import { store } from '@/routes/reviews';
import { useForm } from '@inertiajs/react';
import { Star } from 'lucide-react';
import ErrorMessage from './error-message';
import { Button } from './ui/button';
import { Spinner } from './ui/spinner';

interface ReviewFormData {
    productId: number;
    rating: number;
    comment: string;
}

interface ReviewFormProps {
    productId: number;
}

export default function CreateReviewForm({ productId }: ReviewFormProps) {
    const { data, setData, transform, processing, post, reset, errors } =
        useForm<ReviewFormData>({
            productId,
            rating: 5,
            comment: '',
        });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        transform(() => ({
            product_id: data.productId,
            rating: data.rating,
            comment: data.comment,
        }));

        post(store().url, {
            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <form
            onSubmit={handleSubmit}
            className="mt-4 space-y-4 rounded-md border border-slate-200 bg-slate-50 p-4"
        >
            <div>
                <label className="text-sm font-medium text-slate-700">
                    評価
                </label>

                <div className="flex gap-1">
                    {[1, 2, 3, 4, 5].map((value) => (
                        <button
                            key={value}
                            type="button"
                            onClick={() => setData('rating', value)}
                        >
                            <Star
                                className={`size-6 ${
                                    value <= data.rating
                                        ? 'fill-yellow-400 text-yellow-400'
                                        : 'text-slate-300'
                                }`}
                            />
                        </button>
                    ))}
                </div>
            </div>

            <div>
                <label className="text-sm font-medium text-slate-700">
                    コメント
                </label>

                <textarea
                    value={data.comment}
                    onChange={(e) => setData('comment', e.target.value)}
                    rows={4}
                    className="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                    placeholder="レビューを入力してください"
                />
            </div>

            <Button type="submit" variant="primary" disabled={processing}>
                {processing && <Spinner />}
                レビューを投稿する
            </Button>
            <ErrorMessage message={errors.rating} />
            <ErrorMessage message={errors.comment} />
        </form>
    );
}
