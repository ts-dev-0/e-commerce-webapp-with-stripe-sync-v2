import { Button } from '@/components/ui/button';
import AccountLayout from '@/layouts/account-layout';
import { useModalStore } from '@/stores/modalStore';
import { Order } from '@/types/order';

interface OrderCancelProps {
    order: Order;
}

// construct directoryにfileを作り、移動させる
const STATUS_LABELS = {
    Pending: '保留中',
    Paid: '処理中',
    Completed: '発送済み',
    Canceled: 'キャンセル',
};

const STATUS_COLORS = {
    Pending: 'bg-slate-100 text-slate-700',
    Paid: 'bg-blue-100 text-blue-700',
    Completed: 'bg-yellow-100 text-yellow-700',
    Canceled: 'bg-red-100 text-red-700',
};

export default function CancelOrder({ order }: OrderCancelProps) {
    const openModal = useModalStore((state) => state.openModal);

    return (
        <AccountLayout title="注文キャンセル">
            <div
                key={order.orderId}
                className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
            >
                <div className="flex items-center justify-between">
                    <div className="flex-1">
                        <div className="flex items-center gap-3">
                            <p className="mt-1 text-sm text-slate-600">
                                注文日: {order.orderedAt}
                            </p>
                            <span
                                className={`inline-block rounded-full px-3 py-1 text-xs font-medium ${
                                    STATUS_COLORS[order.status]
                                }`}
                            >
                                {STATUS_LABELS[order.status]}
                            </span>
                            <div className="flex flex-col">
                                <span className="text-xs text-slate-700">
                                    お届け先
                                </span>
                            </div>
                            <div className="text-xs text-slate-500">
                                注文番号: {order.orderNumber}
                            </div>
                        </div>
                    </div>
                    <div className="text-right">
                        <p className="text-lg font-bold text-slate-800">
                            ¥{order.totalAmount.toLocaleString('ja-JP')}
                        </p>
                    </div>
                </div>

                <div className="mt-4 border-t border-slate-200 pt-4">
                    <p className="mb-3 text-sm font-medium text-slate-700">
                        商品
                    </p>
                    <div className="space-y-2">
                        {order.items.map((item) => (
                            <div
                                key={item.id}
                                className="flex items-center justify-between text-sm text-slate-600"
                            >
                                <div className="flex-1">
                                    <span>{item.productName}</span>
                                    <span className="ml-2 text-slate-500">
                                        × {item.quantity}
                                    </span>
                                </div>
                                <div className="flex items-center gap-2">
                                    <span className="font-medium text-slate-800">
                                        {(
                                            item.price * item.quantity
                                        ).toLocaleString('ja-JP')}
                                        円
                                    </span>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
            <div className="flex items-center justify-end py-4">
                <Button
                    type="button"
                    variant={'destructive'}
                    onClick={() =>
                        openModal('cancelOrderConfirm', { id: order.orderId })
                    }
                >
                    注文をキャンセルする
                </Button>
            </div>
        </AccountLayout>
    );
}
