import { update } from '@/routes/cart';
import { router } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from './ui/button';

interface QuantitySelectorProps {
    productId: number;
    value: number;
    min?: number;
    max?: number;
}

export function QuantitySelector({
    productId,
    value,
    min = 1,
    max = 10,
}: QuantitySelectorProps) {
    const [quantity, setQuantity] = useState(value);

    const updateQuantity = (newQuantity: number) => {
        setQuantity(newQuantity);

        router.put(
            // TODO: use cart_id
            update({ cart: 1 }).url,
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

    const handleMinus = () => {
        if (quantity <= min) return;
        updateQuantity(quantity - 1);
    };

    const handlePlus = () => {
        if (quantity >= max) return;
        updateQuantity(quantity + 1);
    };

    return (
        <div className="flex items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-2 py-1">
            <Button
                type="button"
                variant="outline"
                size="icon"
                className="h-7 w-7 p-0"
                aria-label="数量を減らす"
                onClick={handleMinus}
            >
                -
            </Button>
            <span className="text-xs text-slate-700">{quantity}</span>
            <Button
                type="button"
                variant="outline"
                size="icon"
                className="h-7 w-7 p-0"
                aria-label="数量を増やす"
                onClick={handlePlus}
            >
                +
            </Button>
        </div>
    );
}
