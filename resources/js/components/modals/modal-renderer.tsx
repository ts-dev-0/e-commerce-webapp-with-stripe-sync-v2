import { useModalStore } from '@/stores/modalStore';
import CancelOrderConfirmModal from './cancel-order-confirm-modal';
import CreateShippingAddressModal from './create-shipping-address-modal';
import DeleteReviewModal from './delete-review-modal';
import DeleteShippingAddressModal from './delete-shipping-address-modal';
import EditShippingAddressModal from './edit-shipping-address-modal';

export default function ModalRenderer() {
    const modal = useModalStore((state) => state.modal);

    if (modal.type === null) return null;

    switch (modal.type) {
        case 'createShippingAddress':
            return <CreateShippingAddressModal />;
        case 'editShippingAddress':
            return <EditShippingAddressModal address={modal.props} />;
        case 'deleteReviewConfirm':
            return <DeleteReviewModal id={modal.props.id} />;
        case 'deleteShippingAddress':
            return <DeleteShippingAddressModal id={modal.props.id} />;
        case 'cancelOrderConfirm':
            return (
                <CancelOrderConfirmModal
                    id={modal.props.id}
                    orderNumber={modal.props.orderNumber}
                    totalAmount={modal.props.totalAmount}
                    items={modal.props.items}
                />
            );

        default:
            return null;
    }
}
