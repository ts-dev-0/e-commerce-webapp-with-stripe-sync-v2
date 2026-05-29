import { useModalStore } from '@/stores/modalStore';
import { Address } from '@/types/address';
import AddressCard from './address-card';
import ErrorMessage from './error-message';
import { Button } from './ui/button';

interface DeliveryAddressSectionProps {
    addresses: Address[];
    selectedAddressId?: number;
    setData: (id: number) => void;
    processing: boolean;
    reset: () => void;
    setDefaultAddress: (e: React.FormEvent) => void;
    isExpanded: boolean;
    setIsExpanded: (isExpanded: boolean) => void;
    errorMessage?: string;
}

export function DeliveryAddressSection({
    addresses,
    selectedAddressId,
    setData,
    processing,
    reset,
    setDefaultAddress,
    isExpanded,
    setIsExpanded,
    errorMessage,
}: DeliveryAddressSectionProps) {
    const openModal = useModalStore((state) => state.openModal);

    return (
        <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div className="flex items-center justify-between">
                <h2 className="text-lg font-semibold text-slate-800">
                    登録済み配送先
                </h2>

                <Button
                    type="button"
                    variant="primary"
                    onClick={() => openModal('createDeliveryAddress')}
                >
                    新しい配送先を登録する
                </Button>
            </div>
            {addresses.length === 0 ? (
                <div className="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                    登録済みの配送先がありません。
                    <div className="mt-2">新しい配送先を入力してください。</div>
                </div>
            ) : (
                <div className="mt-4 space-y-4">
                    <AddressCard
                        address={addresses[0]}
                        isSelected={selectedAddressId === addresses[0].id}
                        onSelect={setData}
                    />

                    {isExpanded &&
                        addresses
                            .slice(1)
                            .map((address) => (
                                <AddressCard
                                    key={address.id}
                                    address={address}
                                    isSelected={
                                        selectedAddressId === address.id
                                    }
                                    onSelect={setData}
                                />
                            ))}

                    {!isExpanded && addresses.length > 1 && (
                        <button
                            type="button"
                            onClick={() => setIsExpanded(true)}
                            className="text-sm text-indigo-600 hover:underline"
                        >
                            他の配送先を表示
                        </button>
                    )}

                    {isExpanded && (
                        <div className="flex items-center justify-between">
                            <form onSubmit={setDefaultAddress}>
                                <Button
                                    type="submit"
                                    variant="primary"
                                    disabled={processing}
                                >
                                    この住所に配達する
                                </Button>
                            </form>
                            <ErrorMessage message={errorMessage} />

                            <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                onClick={() => {
                                    setIsExpanded(false);
                                    reset();
                                }}
                            >
                                他の配送先を閉じる
                            </Button>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}
