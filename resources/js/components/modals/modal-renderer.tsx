import { useModalStore } from '@/stores/modalStore';
import CreateDeliveryAddressModal from './create-delivery-address-modal';
import DeleteDeliveryAddressModal from './delete-delivery-address-modal';
import DeleteReviewModal from './delete-review-modal';
import EditDeliveryAddressModal from './edit-delivery-address-modal';

export default function ModalRenderer() {
    const modal = useModalStore((state) => state.modal);

    if (modal.type === null) return null;

    switch (modal.type) {
        case 'createDeliveryAddress':
            return <CreateDeliveryAddressModal />;
        case 'editDeliveryAddress':
            return <EditDeliveryAddressModal address={modal.props} />;
        case 'deleteReviewConfirm':
            return <DeleteReviewModal id={modal.props.id} />;
        case 'deleteDeliveryAddress':
            return <DeleteDeliveryAddressModal id={modal.props.id} />;

        default:
            return null;
    }
}
