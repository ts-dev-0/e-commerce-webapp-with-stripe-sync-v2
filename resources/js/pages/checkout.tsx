import { Head, router, useForm } from '@inertiajs/react';

import AppLayout from '@/layouts/app-layout';

import CartItemCard from '@/components/cart-item-card';
import { DeliveryAddressSection } from '@/components/delivery-address-section';
import { PaymentMethodSection } from '@/components/payment-method-section';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { store } from '@/routes/checkout';

import ErrorMessage from '@/components/error-message';
import { update } from '@/routes/addresses/default';
import { Checkout as CheckoutType } from '@/types/checkout';
import React, { useState } from 'react';

interface CheckoutProps {
    checkout: CheckoutType;
}

interface SetDefaultAddressForm {
    selectAddressId?: number;
}

export default function Checkout({
    checkout: { cartItems, addresses, shippingFee, subtotal, total },
}: CheckoutProps) {
    const [isExpanded, setIsExpanded] = useState(false);

    const { data, setData, patch, processing, reset, errors } =
        useForm<SetDefaultAddressForm>({
            selectAddressId: addresses[0].id,
        });

    const handleSetDefaultAddress = (e: React.FormEvent) => {
        e.preventDefault();

        if (data.selectAddressId === undefined) return;

        patch(update(data.selectAddressId).url, {
            preserveState: false,
            onSuccess: () => {
                setIsExpanded(false);
            },
        });
    };

    function handleCheckout(e: React.FormEvent) {
        e.preventDefault();

        if (data.selectAddressId === undefined) return;

        router.post(store().url, {
            address_id: data.selectAddressId,
        });
    }

    return (
        <AppLayout>
            <Head title="Checkout" />

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid gap-6 lg:grid-cols-3">
                    <section className="lg:col-span-2">
                        <div className="space-y-6">
                            <DeliveryAddressSection
                                addresses={addresses}
                                selectedAddressId={data.selectAddressId}
                                setData={(id: number) =>
                                    setData({ selectAddressId: id })
                                }
                                processing={processing}
                                reset={() => reset()}
                                setDefaultAddress={handleSetDefaultAddress}
                                isExpanded={isExpanded}
                                setIsExpanded={(isExpanded) =>
                                    setIsExpanded(isExpanded)
                                }
                                errorMessage={errors.selectAddressId}
                            />
                            <PaymentMethodSection />
                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <div className="flex">
                                    <div className="mt-4 max-w-96 space-y-4">
                                        {cartItems.map(
                                            ({ id, product, quantity }) => {
                                                return (
                                                    <CartItemCard
                                                        key={id}
                                                        cartItemId={id}
                                                        product={product}
                                                        initialQuantity={
                                                            quantity
                                                        }
                                                    />
                                                );
                                            },
                                        )}
                                    </div>
                                    <div className="w-full p-6">
                                        <h2 className="text-lg font-semibold text-slate-800">
                                            配達日時
                                        </h2>

                                        <div className="mt-4 flex items-center justify-between">
                                            <div className="flex items-center gap-x-2">
                                                <Input
                                                    type="radio"
                                                    name="deliveryDate"
                                                    value={'delivery-date'}
                                                    defaultChecked
                                                    className="h-4 w-4 accent-indigo-600 focus:ring-indigo-500"
                                                />

                                                <Label
                                                    htmlFor={'delivery-date'}
                                                    className="text-sm font-medium text-slate-700"
                                                >
                                                    発送後 0～3営業日
                                                </Label>
                                            </div>

                                            <p className="text-sm">
                                                {shippingFee} 円
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* Order Summary  */}
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

                        <form onSubmit={handleCheckout}>
                            <Button
                                type="submit"
                                variant="primary"
                                className="mt-6 w-full"
                            >
                                注文を確定する
                            </Button>
                            <ErrorMessage message={errors.selectAddressId} />
                        </form>
                    </aside>
                </div>
            </div>
        </AppLayout>
    );
}
