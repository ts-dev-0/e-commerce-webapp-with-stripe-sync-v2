import { useModalStore } from '@/stores/modalStore';
import CreateDeliveryAddressModal from './create-delivery-address-modal';
import DeleteReviewModal from './delete-review-modal';

export default function ModalRenderer() {
    const modal = useModalStore((state) => state.modal);

    if (modal.type === null) return null;

    switch (modal.type) {
        case 'createDeliveryAddress':
            return <CreateDeliveryAddressModal />;
        case 'deleteReviewConfirm':
            return <DeleteReviewModal id={modal.props.id} />;

        default:
            return null;
    }
}
