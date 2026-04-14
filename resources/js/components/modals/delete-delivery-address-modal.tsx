import { destroy } from '@/routes/addresses';
import { useModalStore } from '@/stores/modalStore';
import { useForm } from '@inertiajs/react';
import React from 'react';
import ErrorMessage from '../error-message';
import { Button } from '../ui/button';
import { Spinner } from '../ui/spinner';
import ModalWrapper from './modal-wrapper';

interface DeleteDeliveryAddressModalProps {
    id: number;
}

export default function DeleteDeliveryAddressModal({
    id,
}: DeleteDeliveryAddressModalProps) {
    const { processing, delete: deleteAddress, errors } = useForm({ id });
    const closeModal = useModalStore((state) => state.closeModal);

    function handleDeleteAddress(event: React.FormEvent) {
        event.preventDefault();

        deleteAddress(destroy(id).url, {
            onSuccess: () => {
                closeModal();
            },
        });
    }

    return (
        <ModalWrapper>
            <div className="space-y-4">
                <div>
                    <h2 className="text-lg font-semibold text-slate-800">
                        住所を削除しますか？
                    </h2>

                    <p className="mt-1 text-sm text-slate-500">
                        この操作は取り消せません。
                    </p>
                </div>

                <form onSubmit={handleDeleteAddress}>
                    <div className="flex justify-end gap-2">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={closeModal}
                        >
                            キャンセル
                        </Button>

                        <Button
                            type="submit"
                            variant="destructive"
                            disabled={processing}
                        >
                            {processing && <Spinner />}
                            削除する
                        </Button>
                    </div>
                    <div className="text-end">
                        <ErrorMessage message={errors.id} />
                    </div>
                </form>
            </div>
        </ModalWrapper>
    );
}
