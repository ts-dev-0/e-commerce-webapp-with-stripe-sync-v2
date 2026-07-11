import { ImagePlaceholder } from '@/components/image-placeholder';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { detail } from '@/routes/product';
import { useModalStore } from '@/stores/modalStore';
import { Order } from '@/types/order';

interface Props {
    order: Order;
}

const STATUS_CONFIG = {
    Pending: {
        label: '保留',
        border: 'border-slate-200',
        bg: 'bg-slate-100',
        text: 'text-slate-800',
    },
    Paid: {
        label: '支払い済み',
        border: 'border-sky-200',
        bg: 'bg-sky-100',
        text: 'text-sky-800',
    },
    Completed: {
        label: '完了',
        border: 'border-green-200',
        bg: 'bg-green-100',
        text: 'text-green-800',
    },
    Canceled: {
        label: 'キャンセル',
        border: 'border-red-200',
        bg: 'bg-red-100',
        text: 'text-red-800',
    },
} as const;

export function OrderCard({ order }: Props) {
    const openModal = useModalStore((state) => state.openModal);
    const statusConfig = STATUS_CONFIG[order.status];

    return (
        <div className={cn('rounded-2xl border', statusConfig.border)}>
            <div
                className={cn(
                    'flex items-center justify-between rounded-t-2xl p-4 text-xs',
                    statusConfig.bg,
                    statusConfig.text,
                )}
            >
                <span>{statusConfig.label}</span>
                <div>
                    <p>注文日</p>
                    <p>{order.orderedAt}</p>
                </div>
                <div>
                    <p>合計額</p>
                    <p>￥{order.totalAmount.toLocaleString('ja-JP')}</p>
                </div>
                <div>
                    <p>お届け先</p>
                    <p>{order.fullName}</p>
                </div>
                <p>注文番号: {order.orderNumber}</p>
            </div>

            {/* Order Card Content */}
            <div className="flex flex-col gap-3">
                {order.items.map((item) => (
                    <div key={item.id} className="flex p-3">
                        <div className="size-24">
                            <ImagePlaceholder />
                        </div>
                        <div className="line-clamp-1 flex-1 px-2 wrap-break-word">
                            <TextLink
                                href={detail(item.productId)}
                                className="text-blue-800"
                            >
                                {item.productName}
                            </TextLink>
                        </div>
                        <div className="flex flex-col gap-3">
                            {order.status === 'Pending' && (
                                <Button
                                    variant={'outline'}
                                    onClick={() =>
                                        openModal('cancelOrderConfirm', {
                                            id: order.orderId,
                                            orderNumber: order.orderNumber,
                                            totalAmount: order.totalAmount,
                                            items: order.items,
                                        })
                                    }
                                >
                                    キャンセルする
                                </Button>
                            )}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
