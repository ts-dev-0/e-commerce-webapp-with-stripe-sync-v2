import { useModalStore } from '@/stores/modalStore';
import { Address } from '@/types/address';
import { useState } from 'react';
import AddressCard from './address-card';
import { Button } from './ui/button';

interface DeliveryAddressSectionProps {
    addresses: Address[];
}

export function DeliveryAddressSection({
    addresses,
}: DeliveryAddressSectionProps) {
    const [isSelectedAddresId, setSelectedAddressId] = useState(
        addresses.find((address) => address.isDefault)?.id,
    );

    const openModal = useModalStore((state) => state.openModal);

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
                {addresses.length > 0 ? (
                    <>
                        {addresses.map((address) => (
                            <AddressCard
                                key={address.id}
                                address={address}
                                isSelected={isSelectedAddresId === address.id}
                                onSelect={setSelectedAddressId}
                            />
                        ))}
                    </>
                ) : (
                    <div className="rounded-lg border border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                        登録済みの配送先がありません。
                        <div className="mt-2">
                            新しい配送先を入力してください。
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
