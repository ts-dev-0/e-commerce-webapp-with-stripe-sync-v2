import CartItemCard from '@/components/cart-item-card';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/checkout';
import { Cart as CartType } from '@/types/cart';
import { Head, Link } from '@inertiajs/react';

interface CartProps {
    data: CartType;
}

export default function Cart({ data }: CartProps) {
    const cartItems = data['items'];
    const subtotal = data['subtotal'];

    return (
        <AppLayout>
            <Head title="Cart" />
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid gap-6 lg:grid-cols-3">
                    <section className="lg:col-span-2">
                        <div className="space-y-4">
                            {cartItems.length <= 0 ? (
                                <>
                                    <h1 className="text-2xl font-bold text-slate-800">
                                        カートは空です
                                    </h1>
                                    <p className="text-sm text-slate-600">
                                        ショッピングカートをご利用ください。食料品、衣類、生活用品、電子機器などを入れましょう。{' '}
                                    </p>
                                </>
                            ) : (
                                cartItems.map((item) => {
                                    const product = item['product'];
                                    const quantity = item['quantity'];

                                    return (
                                        <CartItemCard
                                            key={product.id}
                                            product={product}
                                            initialQuantity={quantity}
                                        />
                                    );
                                })
                            )}
                        </div>
                    </section>

                    <aside className="h-fit rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 className="text-lg font-semibold text-slate-800">
                            注文内容
                        </h2>
                        <dl className="mt-4 space-y-2 text-sm text-slate-600">
                            <div className="flex justify-between">
                                <dt>小計</dt>
                                <dd>{subtotal.toLocaleString('ja-JP')}円</dd>
                            </div>
                        </dl>

                        <Link href={index()}>
                            <Button
                                className="mt-6 w-full cursor-pointer rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                                variant="default"
                            >
                                購入手続きへ進む
                            </Button>
                        </Link>
                    </aside>
                </div>
            </div>
        </AppLayout>
    );
}
