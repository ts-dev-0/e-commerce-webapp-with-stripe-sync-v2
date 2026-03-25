import AddressCard from '@/components/address-card';
import CartItemCard from '@/components/cart-item-card';
import CreateDeliveryAddressForm from '@/components/create-delivery-address-form';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { store } from '@/routes/checkout';
import { Checkout as CheckoutType } from '@/types/checkout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

interface CheckoutProps {
    data: CheckoutType;
}

export default function Checkout({ data }: CheckoutProps) {
    const cartItems = data['cartItems'];
    const subtotal = data['subtotal'];
    const deliveryDateList = data['deliveryDate'];
    const addresses = data['addresses'];
    const shippingFee = data['shippingFee'];
    const total = data['total'];

    const [isSelectedAddresId, setSelectedAddressId] = useState(
        addresses.find((address) => address.isDefault)?.id,
    );

    const [deliveryDate, setDeliveryDate] = useState<string>(
        deliveryDateList[0],
    );

    function handleCheckout() {
        router.post(store().url);
    }

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
                                    {addresses.length > 0 ? (
                                        <>
                                            {addresses.map((address) => (
                                                <AddressCard
                                                    key={address.id}
                                                    address={address}
                                                    isSelected={
                                                        isSelectedAddresId ===
                                                        address.id
                                                    }
                                                    onSelect={
                                                        setSelectedAddressId
                                                    }
                                                />
                                            ))}
                                        </>
                                    ) : (
                                        <div className="rounded-lg border border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                                            登録済みの配送先がありません。
                                            <div className="mt-2">
                                                新しい配送先を入力してください。
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    配送先情報
                                </h2>

                                <CreateDeliveryAddressForm
                                    processing={true}
                                />
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    配達希望日時
                                </h2>

                                <div className="mt-4">
                                    <label className="text-xs font-medium text-slate-600">
                                        配達希望日時
                                    </label>
                                    <div className="mt-4 flex flex-col gap-2">
                                        {deliveryDateList.map((date) => (
                                            <div
                                                key={date}
                                                className="flex items-center gap-x-2"
                                            >
                                                <Input
                                                    type="radio"
                                                    id={date}
                                                    name="deliveryDate"
                                                    value={date}
                                                    checked={
                                                        deliveryDate === date
                                                    }
                                                    onChange={(e) =>
                                                        setDeliveryDate(
                                                            e.target.value,
                                                        )
                                                    }
                                                    className="h-4 w-4 accent-emerald-600 focus:ring-emerald-500"
                                                />

                                                <Label
                                                    htmlFor={date}
                                                    className="text-sm font-medium text-slate-700"
                                                >
                                                    {date}
                                                </Label>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    支払い方法
                                </h2>

                                <div className="mt-4 space-y-4">
                                    <div className="flex items-center gap-3">
                                        <Input
                                            type="radio"
                                            name="payment"
                                            id="payment-stripe"
                                            defaultChecked
                                            className="h-4 w-4 accent-emerald-600 focus:ring-emerald-500"
                                        />
                                        <label
                                            htmlFor="payment-stripe"
                                            className="text-sm font-medium text-slate-700"
                                        >
                                            Stripe（準備中）
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
                                                key={product.id}
                                                product={product}
                                                initialQuantity={quantity}
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
                                <span>
                                    {shippingFee.toLocaleString('ja-JP')}円
                                </span>
                            </div>
                            <div className="mt-2 flex justify-between text-base font-semibold text-slate-800">
                                <span>合計</span>
                                <span>{total.toLocaleString('ja-JP')}円</span>
                            </div>
                        </div>

                        <Button
                            className="mt-6 w-full rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                            variant="default"
                            disabled={true}
                            onClick={handleCheckout}
                        >
                            注文を確定する
                        </Button>
                    </aside>
                </div>
            </div>
        </AppLayout>
    );
}
