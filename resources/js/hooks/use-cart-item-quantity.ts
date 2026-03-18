import { destroy, update } from '@/routes/cart/items';
import { router } from '@inertiajs/react';
import { useState } from 'react';

interface Props {
    productId: number;
    initialQuantity: number;
    min?: number;
    max?: number;
}

export function useCartItemQuantity({
    productId,
    initialQuantity,
    min = 1,
    max = 10,
}: Props) {
    const [quantity, setQuantity] = useState<number>(initialQuantity);

    const updateQuantity = (newQuantity: number) => {
        setQuantity(newQuantity);

        router.patch(
            update().url,
            {
                product_id: productId,
                quantity: newQuantity,
            },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (page) => {
                    console.log('通信成功:', page);
                },
                onError: (errors) => {
                    console.error('バックエンドのエラー:', errors);
                    setQuantity(quantity);
                },
            },
        );
    };

    function decrement() {
        if (quantity <= min) return;

        updateQuantity(quantity - 1);
    }

    function increment() {
        if (quantity >= max) return;

        updateQuantity(quantity + 1);
    }

    function remove() {
        router.delete(destroy.url(), {
            data: { product_id: productId },
            preserveScroll: true,
            preserveState: true,
            onError: (errors) => {
                console.error(errors);
            },
        });
    }

    return {
        quantity,
        decrement,
        increment,
        remove,
    };
}
