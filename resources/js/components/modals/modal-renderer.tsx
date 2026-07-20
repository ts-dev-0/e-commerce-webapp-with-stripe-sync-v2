import { useModalStore } from '@/stores/modalStore';
import CancelOrderConfirmModal from './cancel-order-confirm-modal';
import CreateShippingAddressModal from './create-shipping-address-modal';
import DeleteDeliveryAddressModal from './delete-delivery-address-modal';
import DeleteReviewModal from './delete-review-modal';
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
        case 'deleteDeliveryAddress':
            return <DeleteDeliveryAddressModal id={modal.props.id} />;
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
