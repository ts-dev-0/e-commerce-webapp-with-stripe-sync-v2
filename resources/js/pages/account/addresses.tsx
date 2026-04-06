import { Button } from '@/components/ui/button';
import AccountLayout from '@/layouts/account-layout';
import { useModalStore } from '@/stores/modalStore';
import { Address } from '@/types/address';
import { Head } from '@inertiajs/react';

interface AddressesProps {
    data: Address[];
}
export default function Addresses({ data }: AddressesProps) {
    const addresses = data;
    const openModal = useModalStore((state) => state.openModal);

    return (
        <AccountLayout
            title="配送先住所"
            description="登録済みの配送先住所一覧です"
        >
            <Head title="配送先住所" />

            <div className="mb-6 flex items-center justify-end">
                <Button
                    className="bg-emerald-600 hover:bg-emerald-700"
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
                <div className="grid gap-4 md:grid-cols-2">
                    {addresses.map((address) => (
                        <div
                            key={address.id}
                            className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
                        >
                            <div className="flex items-start justify-between">
                                <div className="flex-1">
                                    <div className="flex items-center gap-2">
                                        <h2 className="font-semibold text-slate-800">
                                            {address.fullName}
                                        </h2>
                                        {address.isDefault && (
                                            <span className="inline-block rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">
                                                デフォルト
                                            </span>
                                        )}
                                    </div>
                                    <p className="mt-1 text-sm text-slate-600">
                                        {address.phoneNumber}
                                    </p>
                                </div>
                            </div>

                            <div className="mt-4 space-y-2 border-t border-slate-200 pt-4">
                                <p className="text-sm text-slate-800">
                                    <span className="font-medium">
                                        郵便番号：
                                    </span>
                                    {address.postalCode}
                                </p>
                                <p className="text-sm text-slate-800">
                                    <span className="font-medium">住所：</span>
                                    {address.prefecture}
                                    {address.city}
                                    {address.addressLine}
                                </p>
                            </div>

                            <div className="mt-4 flex justify-end gap-2 border-t border-slate-200 pt-4">
                                <Button
                                    className="bg-emerald-600 hover:bg-emerald-700"
                                    onClick={() =>
                                        openModal(
                                            'editDeliveryAddress',
                                            address,
                                        )
                                    }
                                >
                                    編集
                                </Button>

                                <Button
                                    type="button"
                                    variant="destructive"
                                    onClick={() =>
                                        openModal('deleteDeliveryAddress', {
                                            id: address.id,
                                        })
                                    }
                                >
                                    削除
                                </Button>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </AccountLayout>
    );
}
