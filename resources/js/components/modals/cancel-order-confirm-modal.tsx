import cancel from '@/routes/account/orders/cancel';
import { useModalStore } from '@/stores/modalStore';
import { OrderItem } from '@/types/order-item';
import { useForm } from '@inertiajs/react';
import { XIcon } from 'lucide-react';
import React from 'react';
import ErrorMessage from '../error-message';
import { Button } from '../ui/button';
import { Spinner } from '../ui/spinner';
import ModalWrapper from './modal-wrapper';

interface Props {
    id: number;
    orderNumber: string;
    totalAmount: number;
    items: OrderItem[];
}

interface CancelOrderForm {
    id: number;
}

export default function CancelOrderConfirmModal({
    id,
    orderNumber,
    totalAmount,
    items,
}: Props) {
    const closeModal = useModalStore((state) => state.closeModal);
    const { data, patch, processing, errors } = useForm<CancelOrderForm>({
        id,
    });

    function handleCancelOrder(e: React.FormEvent) {
        e.preventDefault();

        patch(cancel.update(data.id).url, {
            onSuccess: () => {
                closeModal();
            },
        });
    }

    return (
        <ModalWrapper>
            <div className="flex flex-col gap-6">
                <div className="flex justify-between gap-4">
                    <div className="flex flex-col gap-2">
                        <h2 className="text-xl font-semibold text-slate-900">
                            注文をキャンセルしますか？
                        </h2>
                        <p className="text-sm text-slate-500">
                            注文をキャンセルすると、処理を元に戻すことはできません。
                        </p>
                    </div>
                    <Button size="icon" variant="ghost" onClick={closeModal}>
                        <XIcon className="size-4" />
                    </Button>
                </div>

                <div className="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm">
                    <div className="flex flex-col gap-1">
                        <span className="text-xs text-slate-500">注文番号</span>
                        <span className="font-medium text-slate-900">
                            {orderNumber}
                        </span>
                    </div>

                    <div className="flex flex-col gap-1">
                        <span className="text-xs text-slate-500">合計金額</span>
                        <span className="font-medium text-slate-900">
                            ￥{totalAmount.toLocaleString('ja-JP')}
                        </span>
                    </div>
                </div>

                <div className="flex flex-col gap-3 rounded-2xl border border-slate-200 p-4">
                    <div className="flex items-center justify-between">
                        <p className="text-sm font-semibold text-slate-800">
                            注文商品
                        </p>
                        <span className="text-xs text-slate-500">
                            合計 {items.length} 件
                        </span>
                    </div>

                    <ol className="flex flex-col gap-3">
                        {items.map((item) => (
                            <li
                                key={item.id}
                                className="flex flex-wrap items-center justify-between gap-2 rounded-2xl bg-white p-3"
                            >
                                <div className="min-w-0 flex-1">
                                    <p className="line-clamp-1 text-sm font-medium wrap-break-word text-slate-900">
                                        {item.productName}
                                    </p>
                                </div>
                                <div className="text-sm text-slate-500">
                                    × {item.quantity}
                                </div>
                            </li>
                        ))}
                    </ol>
                </div>

                <form
                    onSubmit={handleCancelOrder}
                    className="flex flex-col gap-4"
                >
                    <div className="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <Button variant="outline" onClick={closeModal}>
                            戻る
                        </Button>

                        <Button
                            type="submit"
                            variant="destructive"
                            disabled={processing}
                        >
                            {processing && <Spinner />}
                            注文をキャンセル
                        </Button>
                    </div>

                    <div className="flex justify-end">
                        <ErrorMessage message={errors.id} />
                    </div>
                </form>
            </div>
        </ModalWrapper>
    );
}
