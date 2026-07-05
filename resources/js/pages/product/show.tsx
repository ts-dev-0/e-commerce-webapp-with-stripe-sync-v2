import ErrorMessage from '@/components/error-message';
import { QuantitySelector } from '@/components/quantity-selector';
import ReviewSection from '@/components/review-section';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { store } from '@/routes/cart/items';
import { Product } from '@/types/product';
import { Review } from '@/types/review';
import { Head, useForm } from '@inertiajs/react';

interface Props {
    product: Product;
    reviews: Review[];
    averageRating: number;
}

interface AddToCartForm {
    productId: number;
    quantity: number;
}

export default function Show({ product, reviews, averageRating }: Props) {
    const form = useForm<AddToCartForm>({
        productId: product.id,
        quantity: 1,
    });

    const handleSubmit: React.FormEventHandler = (e) => {
        e.preventDefault();

        form.transform((data) => ({
            ...data,
            product_id: data.productId,
            quantity: data.quantity,
        }));

        form.submit(store());
    };

    const handleChange = (newQuantity: number) => {
        if (newQuantity < 1 || newQuantity > 10) return;

        form.setData('quantity', newQuantity);
    };

    return (
        <AppLayout>
            <Head title={product.name} />

            <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div className="mt-6 flex flex-col gap-6 lg:flex-row">
                    <div className="lg:w-1/2">
                        <div className="flex h-72 w-full items-center justify-center rounded-lg border border-slate-200 bg-slate-100">
                            <span className="text-sm text-slate-400">
                                画像準備中
                            </span>
                        </div>
                    </div>

                    <div className="lg:w-1/2">
                        <h1 className="text-2xl font-semibold text-slate-800">
                            {product.name}
                        </h1>
                        <p className="mt-2 text-sm text-slate-600">
                            {product.description}
                        </p>

                        <div className="mt-2">
                            <span className="text-sm text-slate-600">
                                {product.manufacturer}
                            </span>
                        </div>

                        <div className="mt-4 flex items-center gap-x-4">
                            <span className="text-xl font-bold text-slate-800">
                                {product.price.toLocaleString('ja-JP')}円
                            </span>
                            <span
                                className={cn(
                                    'text-sm font-semibold text-red-600',
                                    product.stockStatus['status'] ===
                                        'inStock' && 'text-emerald-600',
                                )}
                            >
                                {product.stockStatus['label']}
                            </span>
                        </div>

                        <div className="mt-6 flex items-center space-x-3">
                            <QuantitySelector
                                onChange={handleChange}
                                quantity={form.data.quantity}
                                showTrashIcon={false}
                                processing={form.processing}
                            />
                            <form onSubmit={handleSubmit}>
                                <Button
                                    type="submit"
                                    variant="primary"
                                    disabled={form.processing}
                                >
                                    {form.processing && <Spinner />}
                                    カートに入れる
                                </Button>
                            </form>
                            <ErrorMessage message={form.errors.productId} />
                            <ErrorMessage message={form.errors.quantity} />
                        </div>
                    </div>
                </div>
                <Separator className="my-6 h-px border-0 bg-slate-300" />
                <ReviewSection
                    productId={product.id}
                    reviews={reviews}
                    averageRating={averageRating}
                />
            </div>
        </AppLayout>
    );
}
