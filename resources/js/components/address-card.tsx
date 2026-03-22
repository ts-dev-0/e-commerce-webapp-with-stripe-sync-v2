import { Address } from '@/types/address';
import { Button } from './ui/button';

interface AddressCardProps {
    address: Address;
}

export default function AddressCard({ address }: AddressCardProps) {
    return (
        <Button
            type="button"
            className={
                'flex h-fit w-full items-start justify-start gap-4 rounded-xl border border-slate-300 bg-slate-50 p-4 text-left shadow transition duration-300 hover:bg-slate-100'
            }
        >
            <div className="flex flex-col gap-1.5">
                <div className="flex items-center gap-2">
                    <p className="text-sm font-bold text-slate-900">
                        {address.fullName}
                    </p>
                    {address.isDefault && (
                        <span className="rounded bg-slate-200 px-1.5 py-0.5 text-[10px] font-semibold text-slate-700">
                            デフォルト
                        </span>
                    )}
                </div>

                <div className="flex flex-col gap-0.5 text-[13px] leading-relaxed text-slate-600">
                    <p>〒{address.postalCode}</p>
                    <p>
                        {address.prefecture} {address.city}{' '}
                        {address.addressLine}
                    </p>
                    <p className="mt-1 font-medium text-slate-500">
                        {address.phoneNumber}
                    </p>
                </div>
            </div>
        </Button>
    );
}
