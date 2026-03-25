import { useModalStore } from '@/stores/modalStore';
import { XIcon } from 'lucide-react';
import CreateDeliveryAddressForm from '../create-delivery-address-form';
import { Button } from '../ui/button';

export default function CreateDeliveryAddressModal() {
    const closeModal = useModalStore((state) => state.closeModal);

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div className="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <div className="flex items-center justify-between">
                    <h2 className="text-lg font-semibold">配送先住所を追加</h2>
                    <Button
                        size={'icon'}
                        variant={'ghost'}
                        onClick={closeModal}
                    >
                        <XIcon />
                    </Button>
                </div>

                <CreateDeliveryAddressForm />
            </div>
        </div>
    );
}
