import { destroy, update } from '@/routes/cart/items';
import { show } from '@/routes/product';
import { Product } from '@/types/product';
import { Link, useForm } from '@inertiajs/react';
import { QuantitySelector } from './quantity-selector';

interface CartItemCartProps {
    product: Product;
    initialQuantity: number;
}

interface CartItemQuantityForm {
    productId: number;
    quantity: number;
}

export default function CartItemCard({
    product,
    initialQuantity,
}: CartItemCartProps) {
    const {
        data,
        setData,
        processing,
        transform,
        patch,
        delete: deleteCartItem,
    } = useForm<CartItemQuantityForm>({
        productId: product.id,
        quantity: initialQuantity,
    });

    function updateQuantity(updatedQuantity: number) {
        transform((prev) => ({
            ...prev,
            product_id: data.productId,
            quantity: updatedQuantity,
        }));

        patch(update().url);
    }

    function removeCartItem() {
        transform(() => ({
            ...data,
            product_id: data.productId,
        }));

        deleteCartItem(destroy().url);
    }

    return (
        <div className="relative">
            {processing && (
                <div className="absolute inset-0 z-10 flex items-center justify-center rounded-lg bg-white/70">
                    <div className="h-6 w-6 animate-spin rounded-full border-2 border-slate-300 border-t-slate-600"></div>
                </div>
            )}

            <div
                className={`flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:flex-row ${
                    processing ? 'pointer-events-none opacity-50' : ''
                }`}
            >
                <div className="shrink-0">
                    <div className="flex h-24 w-24 items-center justify-center rounded-md bg-slate-100 text-sm text-slate-400">
                        {processing ? (
                            <div className="h-6 w-6 animate-spin rounded-full border-2 border-slate-300 border-t-slate-600"></div>
                        ) : (
                            '画像準備中'
                        )}
                    </div>
                </div>

                <div className="flex flex-1 flex-col justify-between">
                    <div>
                        <Link href={show(product.id)}>
                            <h2 className="text-sm font-medium text-slate-800 hover:underline hover:underline-offset-2">
                                {product.name}
                            </h2>
                        </Link>
                        <p className="mt-1 line-clamp-2 text-xs text-slate-500">
                            {product.description}
                        </p>
                    </div>

                    <div className="mt-3 flex flex-wrap items-center justify-between gap-3">
                        <span className="text-sm font-semibold text-slate-800">
                            {product.price.toLocaleString('ja-JP')}円
                        </span>

                        <QuantitySelector
                            decrement={() => {
                                const next = data.quantity - 1;

                                setData('quantity', next);
                                updateQuantity(next);
                            }}
                            increment={() => {
                                const next = data.quantity + 1;

                                setData('quantity', next);
                                updateQuantity(next);
                            }}
                            quantity={data.quantity}
                            onRemove={removeCartItem}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
