import { useModalStore } from '@/stores/modalStore';
import { Address } from '@/types/address';
import { useMemo, useState } from 'react';
import AddressCard from './address-card';
import { Button } from './ui/button';

interface DeliveryAddressSectionProps {
    addresses: Address[];
}

export function DeliveryAddressSection({
    addresses,
}: DeliveryAddressSectionProps) {
    const openModal = useModalStore((state) => state.openModal);

    const defaultAddress = useMemo(
        () => addresses.find((address) => address.isDefault),
        [addresses],
    );

    const [selectedAddressId, setSelectedAddressId] = useState(
        defaultAddress?.id,
    );

    const [collapsedAddressId, setCollapsedAddressId] = useState(
        defaultAddress?.id,
    );

    const [isExpanded, setIsExpanded] = useState(false);

    const collapsedAddress = useMemo(
        () => addresses.find((address) => address.id === collapsedAddressId),
        [addresses, collapsedAddressId],
    );

    const handleCollapse = () => {
        setCollapsedAddressId(selectedAddressId);

        setIsExpanded(false);
    };

    const handleExpand = () => {
        setIsExpanded(true);
    };

    if (addresses.length === 0) {
        return (
            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div className="flex items-center justify-between">
                    <h2 className="text-lg font-semibold text-slate-800">
                        登録済み配送先
                    </h2>

                    <Button
                        className="rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                        onClick={() => openModal('createDeliveryAddress')}
                    >
                        新しい配送先を登録する
                    </Button>
                </div>

                <div className="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                    登録済みの配送先がありません。
                    <div className="mt-2">新しい配送先を入力してください。</div>
                </div>
            </div>
        );
    }

    return (
        <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div className="flex items-center justify-between">
                <h2 className="text-lg font-semibold text-slate-800">
                    登録済み配送先
                </h2>

                <Button
                    className="rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                    onClick={() => openModal('createDeliveryAddress')}
                >
                    新しい配送先を登録する
                </Button>
            </div>

            <div className="mt-4 space-y-4">
                {!isExpanded && collapsedAddress && (
                    <AddressCard
                        address={collapsedAddress}
                        isSelected={selectedAddressId === collapsedAddress.id}
                        onSelect={setSelectedAddressId}
                    />
                )}

                {isExpanded &&
                    addresses.map((address) => (
                        <AddressCard
                            key={address.id}
                            address={address}
                            isSelected={selectedAddressId === address.id}
                            onSelect={setSelectedAddressId}
                        />
                    ))}

                {!isExpanded && addresses.length > 1 && (
                    <button
                        onClick={handleExpand}
                        className="text-sm text-emerald-600 hover:underline"
                    >
                        他の配送先を表示
                    </button>
                )}

                {isExpanded && (
                    <Button
                        onClick={handleCollapse}
                        className="rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                    >
                        この住所に配達する
                    </Button>
                )}
            </div>
        </div>
    );
}
