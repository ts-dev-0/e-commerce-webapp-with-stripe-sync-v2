import cancel from '@/routes/account/orders/cancel';
import { useModalStore } from '@/stores/modalStore';
import { useForm } from '@inertiajs/react';
import React from 'react';
import ErrorMessage from '../error-message';
import { Button } from '../ui/button';
import { Spinner } from '../ui/spinner';
import ModalWrapper from './modal-wrapper';

interface CancelOrderForm {
    id: number;
}

export default function CancelOrderConfirmModal({ id }: CancelOrderForm) {
    const closeModal = useModalStore((state) => state.closeModal);
    const {
        data: order,
        patch,
        processing,
        errors,
    } = useForm<CancelOrderForm>({
        id,
    });

    function handleCancelOrder(e: React.FormEvent) {
        e.preventDefault();

        patch(cancel.update(order.id).url, {
            onSuccess: () => {
                closeModal();
            },
        });
    }

    return (
        <ModalWrapper>
            <div className="space-y-2">
                <div>
                    <h2 className="text-lg font-semibold text-slate-800">
                        注文をキャンセルしますか？
                    </h2>

                    <p className="mt-1 text-sm text-slate-500">
                        この操作は取り消すことができません。
                    </p>
                </div>

                <form onSubmit={handleCancelOrder}>
                    <div className="flex justify-end gap-2">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={closeModal}
                        >
                            閉じる
                        </Button>

                        <Button
                            type="submit"
                            variant="destructive"
                            disabled={processing}
                        >
                            {processing && <Spinner />}
                            注文をキャンセルする
                        </Button>
                    </div>
                </form>
                <div className="flex items-center justify-end">
                    <ErrorMessage message={errors.id} />
                </div>
            </div>
        </ModalWrapper>
    );
}
