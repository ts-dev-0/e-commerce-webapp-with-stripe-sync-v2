import { Button } from '@/components/ui/button';
import { update } from '@/routes/addresses/default';
import { useModalStore } from '@/stores/modalStore';
import { Address } from '@/types/address';
import { useForm } from '@inertiajs/react';
import React from 'react';

interface Props {
    address: Address;
}

export function AddressCard({ address }: Props) {
    const openModal = useModalStore((state) => state.openModal);

    const { data, patch } = useForm<{ id: number }>({ id: address.id });

    function handleSetDefaultAddress(e: React.FormEvent) {
        e.preventDefault();
        patch(update(data.id).url);
    }

    return (
        <div className="col-span-1 h-72 w-80 rounded-xl border border-slate-300 shadow">
            <div className="flex h-full flex-col justify-between py-3">
                <div>
                    {address.isDefault && (
                        <>
                            <div className="pl-3">
                                <span className="text-xs text-slate-600">
                                    既定の住所
                                </span>
                            </div>
                            <hr className="my-2" />
                        </>
                    )}
                    <div className="flex flex-col gap-2 px-3 text-sm">
                        <p className="font-semibold">{address.fullName}</p>
                        <p>{address.postalCode}</p>
                        <div className="flex items-center gap-2">
                            <span>
                                {address.prefecture} {address.city}
                            </span>
                        </div>
                        <span>{address.addressLine}</span>
                        <p>電話番号: {address.phoneNumber}</p>
                    </div>
                </div>
                <div className="flex items-center gap-2 px-3">
                    <Button
                        variant="primary"
                        size={'sm'}
                        onClick={() =>
                            openModal('editShippingAddress', address)
                        }
                    >
                        編集
                    </Button>

                    <Button
                        variant="ghost"
                        size={'sm'}
                        onClick={() =>
                            openModal('deleteDeliveryAddress', {
                                id: address.id,
                            })
                        }
                    >
                        削除
                    </Button>
                    {!address.isDefault && (
                        <form onSubmit={handleSetDefaultAddress}>
                            <Button type="submit" variant={'ghost'} size={'sm'}>
                                既定の住所に設定
                            </Button>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
