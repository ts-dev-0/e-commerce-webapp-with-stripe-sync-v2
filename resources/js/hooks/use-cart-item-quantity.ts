import { destroy, update } from '@/routes/cart/items';
import { router } from '@inertiajs/react';
import { useState } from 'react';

interface Props {
    productId: number;
    initialQuantity: number;
    min?: number;
    max?: number;
    setProcessing: React.Dispatch<React.SetStateAction<boolean>>;
}

export function useCartItemQuantity({
    productId,
    initialQuantity,
    min = 1,
    max = 10,
    setProcessing,
}: Props) {
    const [quantity, setQuantity] = useState<number>(initialQuantity);

    const updateQuantity = (newQuantity: number) => {
        setProcessing(true);
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
                    setProcessing(false);
                },
                onError: (errors) => {
                    console.error('バックエンドのエラー:', errors);
                    setQuantity(quantity);
                    setProcessing(false);
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
        setProcessing(true);

        router.delete(destroy.url(), {
            data: { product_id: productId },
            preserveScroll: true,
            preserveState: true,
            onSuccess: (page) => {
                console.log('通信成功:', page);
                setProcessing(false);
            },
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
