import { Product } from '@/types/product';
import { Button } from './ui/button';

interface CartItemCartProps {
    product: Product;
    quantity: number;
}

export default function CartItemCard({ product, quantity }: CartItemCartProps) {
    return (
        <div
            key={product.id}
            className="flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:flex-row"
        >
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
                    <p className="mt-1 text-xs text-slate-500 line-clamp-2">
                        {product.description}
                    </p>
                </div>

                <div className="mt-3 flex flex-wrap items-center justify-between gap-3">
                    <span className="text-sm font-semibold text-slate-800">
                        {product.price.toLocaleString('ja-JP')}円
                    </span>

                    <div className="flex items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-2 py-1">
                        <Button
                            type="button"
                            variant="outline"
                            size="icon"
                            className="h-7 w-7 p-0"
                            aria-label="数量を減らす"
                        >
                            −
                        </Button>
                        <span className="text-xs text-slate-700">
                            {quantity}
                        </span>
                        <Button
                            type="button"
                            variant="outline"
                            size="icon"
                            className="h-7 w-7 p-0"
                            aria-label="数量を増やす"
                        >
                            +
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );
}
