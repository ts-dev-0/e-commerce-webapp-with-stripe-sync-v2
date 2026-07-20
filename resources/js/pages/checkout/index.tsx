import { Head, useForm } from '@inertiajs/react';
import React from 'react';

import AppLayout from '@/layouts/app-layout';

import { store } from '@/routes/checkout';

import ErrorMessage from '@/components/error-message';

import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';

import { Checkout as CheckoutType } from '@/types/checkout';

import { OrderItemSection } from './component/order-item-section';
import { OrderSummarySection } from './component/order-summary-section';
import { PaymentMethodSection } from './component/payment-method-section';
import { ShippingAddressSection } from './component/shipping-address/shipping-address-section';
import { ShippingMethodSection } from './component/shipping-address/shipping-method-section';

interface CheckoutProps {
    checkout: CheckoutType;
}

interface CheckoutForm {
    shippingAddressId: number | null;
}

export default function Index({ checkout }: CheckoutProps) {
    const { data, setData, post, transform, errors } = useForm<CheckoutForm>({
        shippingAddressId:
            checkout.addresses.length > 0 ? checkout.addresses[0].id : null,
    });

    function handleCheckout(e: React.FormEvent) {
        e.preventDefault();

        if (data.shippingAddressId === null) return;

        transform(() => ({
            ...data,
            address_id: data.shippingAddressId,
        }));

        post(store().url);
    }

    return (
        <AppLayout>
            <Head title="Checkout" />

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid gap-6 lg:grid-cols-3">
                    <section className="lg:col-span-2">
                        <div className="space-y-6">
                            <ShippingAddressSection
                                addresses={checkout.addresses}
                                selectedShippingAddressId={
                                    data.shippingAddressId
                                }
                                setShippingAddressId={(id: number) =>
                                    setData('shippingAddressId', id)
                                }
                            />
                            <Separator />

                            <PaymentMethodSection />

                            <Separator />

                            <ShippingMethodSection />

                            <Separator />

                            <OrderItemSection items={checkout.cartItems} />
                        </div>
                    </section>

                    <section className="flex flex-col gap-5 lg:col-span-1">
                        <OrderSummarySection
                            subtotal={checkout.subtotal}
                            shippingFee={checkout.shippingFee}
                            total={checkout.total}
                        />
                        <form onSubmit={handleCheckout}>
                            <div className="flex items-center justify-end">
                                <Button type="submit" variant={'primary'}>
                                    注文を確定する
                                </Button>
                            </div>
                        </form>
                        <ErrorMessage message={errors.shippingAddressId} />
                    </section>
                </div>
            </div>
        </AppLayout>
    );
}
