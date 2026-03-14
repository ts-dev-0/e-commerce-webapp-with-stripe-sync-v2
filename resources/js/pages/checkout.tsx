import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { useState } from 'react';

type CheckoutItem = {
    id: number;
    name: string;
    price: number;
    quantity: number;
};

type ShippingAddress = {
    id: number;
    name: string;
    postal: string;
    address: string;
    phone: string;
};

const dummyItems: CheckoutItem[] = [
    { id: 1, name: 'コットンTシャツ', price: 2500, quantity: 1 },
    { id: 2, name: 'デニムパンツ', price: 7200, quantity: 2 },
    { id: 3, name: 'スニーカー', price: 9800, quantity: 1 },
];

const savedAddresses: ShippingAddress[] = [
    {
        id: 1,
        name: '山田 太郎',
        postal: '〒123-4567',
        address: '東京都渋谷区桜丘町1-1-1',
        phone: '090-1234-5678',
    },
    {
        id: 2,
        name: '佐藤 花子',
        postal: '〒234-5678',
        address: '大阪府大阪市北区梅田2-2-2',
        phone: '080-9876-5432',
    },
];

export default function Checkout() {
    const [selectedAddressId, setSelectedAddressId] = useState<number>(
        savedAddresses[0]?.id ?? 0,
    );

    const subtotal = dummyItems.reduce(
        (sum, item) => sum + item.price * item.quantity,
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
                                    {savedAddresses.map((addr) => {
                                        const isSelected =
                                            selectedAddressId === addr.id;

                                        return (
                                            <button
                                                key={addr.id}
                                                type="button"
                                                onClick={() =>
                                                    setSelectedAddressId(
                                                        addr.id,
                                                    )
                                                }
                                                className={
                                                    'flex w-full items-start justify-between rounded-lg border p-4 text-left ' +
                                                    (isSelected
                                                        ? 'border-emerald-500 bg-emerald-50'
                                                        : 'border-slate-200 bg-slate-50 hover:bg-slate-100')
                                                }
                                            >
                                                <div>
                                                    <p className="text-sm font-semibold text-slate-800">
                                                        {addr.name}
                                                    </p>
                                                    <p className="text-xs text-slate-600">
                                                        {addr.postal}{' '}
                                                        {addr.address}
                                                    </p>
                                                    <p className="text-xs text-slate-600">
                                                        {addr.phone}
                                                    </p>
                                                </div>

                                                <div className="flex h-8 w-8 items-center justify-center rounded-full border text-sm font-semibold">
                                                    {isSelected ? '✓' : ''}
                                                </div>
                                            </button>
                                        );
                                    })}
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
                        </div>
                    </section>

                    <aside className="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 className="text-lg font-semibold text-slate-800">
                            ご注文内容
                        </h2>

                        <div className="mt-4 space-y-3">
                            {dummyItems.map((item) => (
                                <div
                                    key={item.id}
                                    className="flex items-start justify-between"
                                >
                                    <div>
                                        <p className="text-sm font-medium text-slate-800">
                                            {item.name}
                                        </p>
                                        <p className="text-xs text-slate-500">
                                            数量: {item.quantity}
                                        </p>
                                    </div>
                                    <p className="text-sm font-semibold text-slate-800">
                                        {(
                                            item.price * item.quantity
                                        ).toLocaleString('ja-JP')}
                                        円
                                    </p>
                                </div>
                            ))}
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
