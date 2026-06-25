import { Separator } from '@/components/ui/separator';

interface OrderSummarySectionProps {
    subtotal: number;
    shippingFee: number;
    total: number;
}

export function OrderSummarySection({
    subtotal,
    shippingFee,
    total,
}: OrderSummarySectionProps) {
    return (
        <div className="flex flex-col justify-center gap-2">
            <div className="flex items-center justify-between text-sm">
                <span>小計:</span>
                <span>￥{subtotal.toLocaleString('ja-JP')}</span>
            </div>
            <div className="flex items-center justify-between text-sm">
                <span>配送料:</span>
                <span>￥{shippingFee.toLocaleString('ja-JP')}</span>
            </div>
            <Separator className="my-3" />
            <div className="flex items-center justify-between text-lg font-semibold">
                <span className="text-slate-800">合計金額:</span>
                <span className="text-red-500">
                    ￥{total.toLocaleString('ja-JP')}
                </span>
            </div>
        </div>
    );
}
