import { ImagePlaceholder } from '@/components/image-placeholder';
import { StockStatus } from '@/components/stock-status';
import { Product } from '@/types/product';
import { CartItemQuantitySelector } from './cart-item-quantity-selector';

interface CartItemCartProps {
    cartItemId: number;
    product: Product;
    quantity: number;
}

export default function CartItemCard({
    cartItemId,
    product,
    quantity,
}: CartItemCartProps) {
    return (
        <div className="flex">
            <div className="size-28">
                <ImagePlaceholder />
            </div>

            <div className="flex min-w-0 flex-1 flex-col gap-2 p-3">
                <h2 className="line-clamp-2 wrap-break-word">{product.name}</h2>

                <span className="font-semibold text-slate-800">
                    ￥{product.price.toLocaleString('ja-JP')}
                </span>
                <div className="flex items-center gap-5">
                    <StockStatus
                        status={product.stockStatus.status}
                        label={product.stockStatus.label}
                    />
                    <CartItemQuantitySelector
                        productId={product.id}
                        cartItemId={cartItemId}
                        quantity={quantity}
                    />
                </div>
            </div>
        </div>
    );
}
