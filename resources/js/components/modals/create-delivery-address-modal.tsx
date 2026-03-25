import { useModalStore } from '@/stores/modalStore';
import { XIcon } from 'lucide-react';
import CreateDeliveryAddressForm from '../create-delivery-address-form';
import { Button } from '../ui/button';
import ModalWrapper from './modal-wrapper';

export default function CreateDeliveryAddressModal() {
    const closeModal = useModalStore((state) => state.closeModal);

    return (
        <ModalWrapper>
            <div className="flex items-center justify-between">
                <h2 className="text-lg font-semibold">配送先住所を追加</h2>
                <Button size={'icon'} variant={'ghost'} onClick={closeModal}>
                    <XIcon />
                </Button>
            </div>

            <CreateDeliveryAddressForm />
        </ModalWrapper>
    );
}
