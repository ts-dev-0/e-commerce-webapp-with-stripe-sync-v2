import { CartItem } from '@/types/cart-item';

interface OrderItemSectionProps {
    items: CartItem[];
}

export function OrderItemSection({ items }: OrderItemSectionProps) {
    return (
        <div className="flex flex-col justify-center gap-5">
            {items.map((item) => (
                <div key={item.id} className="flex items-center gap-3" >
                    <div className="border border-slate-200 bg-slate-50 px-5 py-7 text-center text-slate-400">
                        <span className="text-xs">画像準備中</span>
                    </div>
                    <h2 className="w-96 truncate">{item.product.name}</h2>
                    <div className="flex flex-1 items-center justify-between">
                        <span>
                            ￥{item.product.price.toLocaleString('ja-JP')}
                        </span>
                        <span>購入個数: {item.quantity}</span>
                    </div>
                </div>
            ))}
        </div>
    );
}
