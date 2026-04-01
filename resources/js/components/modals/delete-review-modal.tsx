import { destroy } from '@/routes/reviews';
import { useModalStore } from '@/stores/modalStore';
import { useForm } from '@inertiajs/react';
import React from 'react';
import { Button } from '../ui/button';
import { Spinner } from '../ui/spinner';
import ModalWrapper from './modal-wrapper';

interface DeleteReviewModalProps {
    id: number;
}

export default function DeleteReviewModal({ id }: DeleteReviewModalProps) {
    const {
        data,
        processing,
        delete: deleteReview,
    } = useForm<{ id: number }>({ id });
    const closeModal = useModalStore((state) => state.closeModal);

    function handleDeleteReview(e: React.FormEvent) {
        e.preventDefault();
        deleteReview(destroy(data.id).url, {
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
                        レビューを削除しますか？
                    </h2>

                    <p className="mt-1 text-sm text-slate-500">
                        この操作は取り消すことができません。
                    </p>
                </div>

                <form onSubmit={handleDeleteReview}>
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
                </form>
            </div>
        </ModalWrapper>
    );
}
