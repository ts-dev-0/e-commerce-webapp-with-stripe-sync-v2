import { Button } from '@/components/ui/button';
import AccountLayout from '@/layouts/account-layout';
import { useModalStore } from '@/stores/modalStore';
import { Address } from '@/types/address';
import { Head } from '@inertiajs/react';
import { AddressCard } from './component/address-card';

interface AddressesProps {
    addresses: Address[];
}
export default function Index({ addresses }: AddressesProps) {
    const openModal = useModalStore((state) => state.openModal);

    return (
        <AccountLayout
            title="配送先住所"
            description="登録済みの配送先住所一覧です"
        >
            <Head title="配送先住所" />

            <div className="mb-6 flex items-center justify-end">
                <Button
                    variant="primary"
                    onClick={() => openModal('createDeliveryAddress')}
                >
                    新しい住所を追加
                </Button>
            </div>

            {addresses.length === 0 ? (
                <div className="rounded-lg border border-slate-200 bg-slate-50 p-8 text-center">
                    <p className="text-slate-600">登録済みの住所がありません</p>
                </div>
            ) : (
                <div className="grid grid-cols-2">
                    {addresses.map((address) => (
                        <AddressCard key={address.id} address={address} />
                    ))}
                </div>
            )}
        </AccountLayout>
    );
}
