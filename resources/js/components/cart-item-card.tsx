import { useCartItemQuantity } from '@/hooks/use-cart-item-quantity';
import { show } from '@/routes/product';
import { Product } from '@/types/product';
import { Link } from '@inertiajs/react';
import { QuantitySelector } from './quantity-selector';

interface CartItemCartProps {
    product: Product;
    initialQuantity: number;
    processing: boolean;
    setProcessing: React.Dispatch<React.SetStateAction<boolean>>;
}

export default function CartItemCard({
    product,
    initialQuantity,
    processing,
    setProcessing,
}: CartItemCartProps) {
    const { quantity, decrement, increment, remove } = useCartItemQuantity({
        productId: product.id,
        initialQuantity,
        setProcessing,
    });
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
                            decrement={decrement}
                            increment={increment}
                            quantity={quantity}
                            onRemove={remove}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
