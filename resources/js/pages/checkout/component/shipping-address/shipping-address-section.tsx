import { EmptyState } from '@/components/empty-state';

import { useModalStore } from '@/stores/modalStore';
import { Address } from '@/types/address';

import { PackageSearch } from 'lucide-react';
import { ShippingAddressHeader } from './shipping-address-header';
import { ShippingAddressList } from './shipping-address-list';

interface DeliveryAddressSectionProps {
    addresses: Address[];
    selectedShippingAddressId: number | null;
    setShippingAddressId: (id: number) => void;
}

export function ShippingAddressSection({
    addresses,
    selectedShippingAddressId,
    setShippingAddressId,
}: DeliveryAddressSectionProps) {
    const openModal = useModalStore((state) => state.openModal);

    return (
        <div className="flex flex-col gap-y-5">
            <ShippingAddressHeader
                onClick={() => openModal('createShippingAddress')}
            />

            {addresses.length === 0 ? (
                <EmptyState
                    title="登録済みの配送先がありません。"
                    description="新しい配送先を登録してください。"
                    icon={<PackageSearch size={40} />}
                />
            ) : (
                <>
                    <ShippingAddressList
                        addresses={addresses}
                        selectedShippingAddressId={selectedShippingAddressId}
                        setShippingAddressId={setShippingAddressId}
                    />
                </>
            )}
        </div>
    );
}
