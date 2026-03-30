import { QuantitySelector } from '@/components/quantity-selector';
import ReviewSection from '@/components/review-section';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/app-layout';
import { store } from '@/routes/cart';
import { Product } from '@/types/product';
import { Head, useForm } from '@inertiajs/react';

interface ShowProps {
    data: Product;
}

interface LocalQuantityForm {
    productId: number;
    quantity: number;
}

export default function Show({ data: product }: ShowProps) {
    const { data, setData, transform, post, processing } =
        useForm<LocalQuantityForm>({
            productId: product.id,
            quantity: 1,
        });

    const handleSubmit: React.FormEventHandler = (e) => {
        e.preventDefault();

        transform(() => ({
            ...data,
            product_id: data.productId,
            quantity: data.quantity,
        }));

        post(store().url);
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

                        <div className="mt-4">
                            <span className="text-xl font-bold text-slate-800">
                                {product.price.toLocaleString('ja-JP')}円
                            </span>
                        </div>

                        <div className="mt-6 flex items-center space-x-3">
                            <QuantitySelector
                                decrement={() =>
                                    setData((prev) => ({
                                        ...prev,
                                        quantity: prev.quantity - 1,
                                    }))
                                }
                                increment={() =>
                                    setData((prev) => ({
                                        ...prev,
                                        quantity: prev.quantity + 1,
                                    }))
                                }
                                quantity={data.quantity}
                            />
                            <form onSubmit={handleSubmit}>
                                <Button
                                    type="submit"
                                    className="cursor-pointer rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                                    disabled={processing}
                                >
                                    {processing && <Spinner />}
                                    カートに入れる
                                </Button>
                            </form>
                        </div>
                    </div>
                </div>
                <Separator className="my-6 h-px border-0 bg-slate-300" />
                <ReviewSection productId={product.id} />
            </div>
        </AppLayout>
    );
}
