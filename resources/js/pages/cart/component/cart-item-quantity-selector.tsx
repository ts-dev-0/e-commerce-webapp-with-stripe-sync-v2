import ErrorMessage from '@/components/error-message';
import { QuantitySelector } from '@/components/quantity-selector';
import { destroy, update } from '@/routes/cart/items';
import { useForm } from '@inertiajs/react';

interface Props {
    productId: number;
    cartItemId: number;
    quantity: number;
}

export function CartItemQuantitySelector({
    productId,
    cartItemId,
    quantity,
}: Props) {
    const form = useForm<{ quantity: number }>({
        quantity: quantity,
    });

    const handleChange = (nextQuantity: number) => {
        if (nextQuantity > 10) return;
        if (nextQuantity < 1) {
            form.transform(() => ({
                product_id: productId,
            }));

            form.submit(destroy(productId));
            return;
        }
        form.setData('quantity', nextQuantity);

        form.transform((prev) => ({
            ...prev,
            product_id: productId,
            quantity: nextQuantity,
        }));

        form.submit(update(cartItemId));
    };

    return (
        <>
            <QuantitySelector
                quantity={form.data.quantity}
                onChange={handleChange}
                showTrashIcon
                processing={form.processing}
            />
            <ErrorMessage message={form.errors.quantity} />
        </>
    );
}
