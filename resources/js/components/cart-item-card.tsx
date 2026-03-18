import { useCartItemQuantity } from '@/hooks/use-cart-item-quantity';
import { Product } from '@/types/product';
import { QuantitySelector } from './quantity-selector';

interface CartItemCartProps {
    product: Product;
    initialQuantity: number;
}

export default function CartItemCard({
    product,
    initialQuantity,
}: CartItemCartProps) {
    const { quantity, decrement, increment, remove } = useCartItemQuantity({
        productId: product.id,
        initialQuantity,
    });
    return (
        <div className="flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:flex-row">
            <div className="shrink-0">
                <div className="flex h-24 w-24 items-center justify-center rounded-md bg-slate-100 text-sm text-slate-400">
                    画像準備中
                </div>
            </div>

            <div className="flex flex-1 flex-col justify-between">
                <div>
                    <h2 className="text-sm font-medium text-slate-800">
                        {product.name}
                    </h2>
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
    );
}
