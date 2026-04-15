import { update } from '@/routes/reviews';
import { useForm } from '@inertiajs/react';
import { Star } from 'lucide-react';
import { Button } from './ui/button';
import { Spinner } from './ui/spinner';

interface EditReviewFormData {
    rating: number;
    comment: string;
}

interface ReviewFormProps {
    reviewId: number;
    rating: number;
    comment: string;
    setEditMode: (editMode: boolean) => void;
}

export default function EditReviewForm({
    reviewId,
    rating,
    comment,
    setEditMode,
}: ReviewFormProps) {
    const { data, setData, transform, processing, patch, reset } =
        useForm<EditReviewFormData>({
            rating,
            comment,
        });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        transform(() => ({
            rating: data.rating,
            comment: data.comment,
        }));

        patch(update(reviewId).url, {
            onSuccess: () => {
                reset();
                setEditMode(false);
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
                        <Button
                            key={value}
                            type="button"
                            variant="ghost"
                            size="icon"
                            onClick={() => setData('rating', value)}
                        >
                            <Star
                                className={`size-6 ${
                                    value <= data.rating
                                        ? 'fill-yellow-400 text-yellow-400'
                                        : 'text-slate-300'
                                }`}
                            />
                        </Button>
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
                    required
                />
            </div>

            <Button type="submit" variant="primary" disabled={processing}>
                {processing && <Spinner />}
                レビューを更新する
            </Button>
        </form>
    );
}
