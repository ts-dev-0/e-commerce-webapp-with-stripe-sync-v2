import { useModalStore } from '@/stores/modalStore';
import CreateDeliveryAddressModal from './create-delivery-address-modal';



export default function ModalRenderer() {
    const modal = useModalStore((state) => state.modal);

    if (modal.type === null) return null;

    switch (modal.type) {
        case 'createDeliveryAddress':
            return <CreateDeliveryAddressModal />;

        default:
            return null;
    }
}
