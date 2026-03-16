import CartItemCard from '@/components/cart-item-card';
// import SavedAddressCard from '@/components/saved-address-card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app-layout';
import { CartItem } from '@/types/cart-item';
import { Head } from '@inertiajs/react';

interface CheckoutProps {
    data: CartItem[];
}

export default function Checkout({ data }: CheckoutProps) {
    const cartItems = data;

    // TODO: Move logic to calculation subtotal to Back-end
    const subtotal = cartItems.reduce(
        (sum, item) => sum + item['product'].price * item['quantity'],
        0,
    );

    const shipping = 0;
    const total = subtotal + shipping;

    return (
        <AppLayout>
            <Head title="Checkout" />

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid gap-6 lg:grid-cols-3">
                    <section className="lg:col-span-2">
                        <div className="space-y-6">
                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    登録済み配送先
                                </h2>

                                <div className="mt-4 space-y-4">
                                    <div className="rounded-lg border border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                                        登録済みの配送先がありません。
                                        <div className="mt-2">
                                            新しい配送先を入力してください。
                                        </div>
                                    </div>
                                    {/* TODO: 住所に関する機能の実装に使用する */}
                                    {/* <SavedAddressCard /> */}
                                </div>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    配送先情報
                                </h2>

                                <div className="mt-4 grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label className="text-xs font-medium text-slate-600">
                                            お名前
                                        </label>
                                        <Input placeholder="山田 太郎" />
                                    </div>

                                    <div>
                                        <label className="text-xs font-medium text-slate-600">
                                            メールアドレス
                                        </label>
                                        <Input
                                            placeholder="example@example.com"
                                            type="email"
                                        />
                                    </div>

                                    <div className="sm:col-span-2">
                                        <label className="text-xs font-medium text-slate-600">
                                            住所
                                        </label>
                                        <Input placeholder="〒123-4567 東京都○○区○○町1-2-3" />
                                    </div>

                                    <div>
                                        <label className="text-xs font-medium text-slate-600">
                                            電話番号
                                        </label>
                                        <Input placeholder="090-1234-5678" />
                                    </div>
                                </div>
                                <Button
                                    className="mt-6 w-full rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                                    variant="default"
                                >
                                    この住所を使用
                                </Button>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    配達希望日時
                                </h2>

                                <div className="mt-4">
                                    <label className="text-xs font-medium text-slate-600">
                                        配達希望日時
                                    </label>
                                    <Input
                                        className="mt-2"
                                        type="datetime-local"
                                    />
                                </div>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    支払い方法
                                </h2>

                                <div className="mt-4 space-y-4">
                                    <div className="flex items-center gap-3">
                                        <input
                                            type="radio"
                                            name="payment"
                                            id="payment-credit"
                                            defaultChecked
                                            className="h-4 w-4 text-primary focus:ring-primary"
                                        />
                                        <label
                                            htmlFor="payment-credit"
                                            className="text-sm font-medium text-slate-700"
                                        >
                                            クレジットカード（準備中）
                                        </label>
                                    </div>

                                    <div className="flex items-center gap-3">
                                        <input
                                            type="radio"
                                            name="payment"
                                            id="payment-cod"
                                            className="h-4 w-4 text-primary focus:ring-primary"
                                        />
                                        <label
                                            htmlFor="payment-cod"
                                            className="text-sm font-medium text-slate-700"
                                        >
                                            代金引換（準備中）
                                        </label>
                                    </div>
                                </div>

                                <p className="mt-4 text-xs text-slate-500">
                                    ※ 本実装では決済処理は行われません。今後
                                    Stripe 等と連携します。
                                </p>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    カート内の商品
                                </h2>

                                <div className="mt-4 space-y-4">
                                    {cartItems.map((item) => {
                                        const product = item['product'];
                                        const quantity = item['quantity'];

                                        return (
                                            <CartItemCard
                                                product={product}
                                                quantity={quantity}
                                            />
                                        );
                                    })}
                                </div>
                            </div>
                        </div>
                    </section>

                    <aside className="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 className="text-lg font-semibold text-slate-800">
                            ご注文内容
                        </h2>

                        <div className="mt-4 space-y-3">
                            {cartItems.map((item) => {
                                const product = item['product'];
                                const quantity = item['quantity'];
                                return (
                                    <div
                                        key={product.id}
                                        className="flex items-start justify-between"
                                    >
                                        <div>
                                            <p className="text-sm font-medium text-slate-800">
                                                {product.name}
                                            </p>
                                            <p className="text-xs text-slate-500">
                                                数量: {quantity}
                                            </p>
                                        </div>
                                        <p className="text-sm font-semibold text-slate-800">
                                            {(
                                                product.price * quantity
                                            ).toLocaleString('ja-JP')}
                                            円
                                        </p>
                                    </div>
                                );
                            })}
                        </div>

                        <div className="mt-6 border-t border-slate-200 pt-4 text-sm text-slate-600">
                            <div className="flex justify-between">
                                <span>小計</span>
                                <span>
                                    {subtotal.toLocaleString('ja-JP')}円
                                </span>
                            </div>
                            <div className="flex justify-between">
                                <span>送料</span>
                                <span>無料</span>
                            </div>
                            <div className="mt-2 flex justify-between text-base font-semibold text-slate-800">
                                <span>合計</span>
                                <span>{total.toLocaleString('ja-JP')}円</span>
                            </div>
                        </div>

                        <Button
                            className="mt-6 w-full rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                            variant="default"
                        >
                            注文を確定する
                        </Button>
                    </aside>
                </div>
            </div>
        </AppLayout>
    );
}
