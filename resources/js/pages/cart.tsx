import CartItemCard from '@/components/cart-item-card';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { Cart as CartType } from '@/types/cart';
import { Head } from '@inertiajs/react';

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
                            {cartItems.map((item) => {
                                const product = item['product'];
                                const quantity = item['quantity'];

                                return (
                                    <CartItemCard
                                        key={product.id}
                                        product={product}
                                        quantity={quantity}
                                    />
                                );
                            })}
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
                            <div className="flex justify-between">
                                <dt>送料</dt>
                                <dd>無料</dd>
                            </div>
                            <div className="flex justify-between font-semibold text-slate-800">
                                <dt>合計</dt>
                                <dd>{subtotal.toLocaleString('ja-JP')}円</dd>
                            </div>
                        </dl>

                        <Button
                            className="mt-6 w-full rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                            variant="default"
                        >
                            購入手続きへ進む
                        </Button>
                    </aside>
                </div>
            </div>
        </AppLayout>
    );
}
