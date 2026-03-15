import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { CartItem } from '@/types/cart-item';
import { Head } from '@inertiajs/react';

interface CartProps {
    data: CartItem[];
}

export default function Cart({ data }: CartProps) {
    const cartItems = data;

    // TODO: Move logic to calculation subtotal to Back-end
    const subtotal = cartItems.reduce(
        (sum, item) => sum + item['product'].price * item['quantity'],
        0,
    );

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
                                    <div
                                        key={product.id}
                                        className="flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:flex-row"
                                    >
                                        <div className="shrink-0">
                                            <div className="flex h-24 w-24 items-center justify-center rounded-md bg-slate-100 text-sm text-slate-400">
                                                画像準備中
                                            </div>
                                        </div>

                                        <div className="flex flex-1 flex-col justify-between">
                                            <div>
                                                <h2 className="text-sm font-medium text-slate-800">
                                                    {product.name}
                                                </h2>
                                                <p className="mt-1 text-xs text-slate-500">
                                                    {product.description}
                                                </p>
                                            </div>

                                            <div className="mt-3 flex flex-wrap items-center justify-between gap-3">
                                                <span className="text-sm font-semibold text-slate-800">
                                                    {product.price.toLocaleString(
                                                        'ja-JP',
                                                    )}
                                                    円
                                                </span>

                                                <div className="flex items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-2 py-1">
                                                    <Button
                                                        type="button"
                                                        variant="outline"
                                                        size="icon"
                                                        className="h-7 w-7 p-0"
                                                        aria-label="数量を減らす"
                                                    >
                                                        −
                                                    </Button>
                                                    <span className="text-xs text-slate-700">
                                                        {quantity}
                                                    </span>
                                                    <Button
                                                        type="button"
                                                        variant="outline"
                                                        size="icon"
                                                        className="h-7 w-7 p-0"
                                                        aria-label="数量を増やす"
                                                    >
                                                        +
                                                    </Button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
