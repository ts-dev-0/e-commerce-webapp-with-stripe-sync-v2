import CartItemCard from '@/components/cart-item-card';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/checkout';
import { Cart as CartType } from '@/types/cart';
import { Head, Link } from '@inertiajs/react';

interface CartProps {
    cart: CartType;
}

export default function Cart({ cart }: CartProps) {
    return (
        <AppLayout>
            <Head title="Cart" />
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid gap-6 lg:grid-cols-3">
                    <section className="lg:col-span-2">
                        <div className="space-y-4">
                            {cart.items.length <= 0 ? (
                                <>
                                    <h1 className="text-2xl font-bold text-slate-800">
                                        カートは空です
                                    </h1>
                                    <p className="text-sm text-slate-600">
                                        ショッピングカートをご利用ください。食料品、衣類、生活用品、電子機器などを入れましょう。{' '}
                                    </p>
                                </>
                            ) : (
                                cart.items.map(({ id, product, quantity }) => {
                                    return (
                                        <CartItemCard
                                            key={id}
                                            cartItemId={id}
                                            product={product}
                                            initialQuantity={quantity}
                                        />
                                    );
                                })
                            )}
                        </div>
                    </section>

                    <aside className="flex h-fit flex-col gap-6 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 className="text-lg font-semibold text-slate-800">
                            注文内容
                        </h2>
                        <dl className="mt-4 space-y-2 text-sm text-slate-600">
                            <div className="flex justify-between">
                                <dt>小計</dt>
                                <dd>
                                    {cart.subtotal.toLocaleString('ja-JP')}円
                                </dd>
                            </div>
                        </dl>

                        <Link href={index()}>
                            <Button
                                type="button"
                                variant="primary"
                                className="w-full"
                                disabled={cart.items.length === 0}
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
