import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { home } from '@/routes';
import { store } from '@/routes/cart';
import { Product } from '@/types/product';
import { Form, Head, Link, useForm } from '@inertiajs/react';
import React from 'react';

interface ShowProps {
    data: Product;
}

interface AddToCartForm {
    product_id: number;
    quantity: number;
}

export default function Show({ data: product }: ShowProps) {
    const { post, processing } = useForm<AddToCartForm>({
        product_id: product.id,
        quantity: 1,
    });

    const handleSubmit: React.FormEventHandler = (e) => {
        e.preventDefault();

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

                        <div className="mt-4">
                            <span className="text-xl font-bold text-slate-800">
                                {product.price.toLocaleString('ja-JP')}円
                            </span>
                        </div>

                        <div className="mt-6 flex items-center space-x-3">
                            <Form onClick={handleSubmit} method="POST">
                                <Button
                                    type="submit"
                                    className="rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700 cursor-pointer"
                                    disabled={processing}
                                >
                                    カートに入れる
                                </Button>
                            </Form>
                            <Link
                                href={home()}
                                className="text-sm text-slate-600 hover:underline"
                            >
                                一覧に戻る
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
