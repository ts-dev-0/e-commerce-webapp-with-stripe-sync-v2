import { useModalStore } from '@/stores/modalStore';
import { Address } from '@/types/address';
import { XIcon } from 'lucide-react';
import EditDeliveryAddressForm from '../edit-delivery-address-form';
import ModalWrapper from './modal-wrapper';

interface EditDeliveryAddressModalProps {
    address: Address;
}

export default function EditDeliveryAddressModal({
    address,
}: EditDeliveryAddressModalProps) {
    const closeModal = useModalStore((state) => state.closeModal);

    return (
        <ModalWrapper>
            <div className="flex items-center justify-between">
                <h2 className="text-lg font-semibold">配送先住所を編集</h2>
                <button
                    type="button"
                    className="rounded-md p-2 text-slate-500 hover:bg-slate-100"
                    onClick={closeModal}
                >
                    <XIcon />
                </button>
            </div>

            <EditDeliveryAddressForm address={address} />
        </ModalWrapper>
    );
}
