import { cn } from '@/lib/utils';
import { Address } from '@/types/address';
import { CheckCircle2 } from 'lucide-react';
import { Button } from './ui/button';
import { Badge } from './ui/badge';

interface AddressCardProps {
    address: Address;
    isSelected: boolean;
    onSelect: (id: number) => void;
}

export default function AddressCard({
    address,
    isSelected,
    onSelect,
}: AddressCardProps) {
    return (
        <Button
            type="button"
            onClick={() => onSelect(address.id)}
            className={cn(
                'flex h-auto w-full items-center justify-start gap-4 rounded-xl border border-slate-300 bg-slate-50 p-4 text-left shadow-sm transition duration-300',
                isSelected
                    ? 'border-indigo-300 bg-indigo-50 hover:bg-indigo-50'
                    : 'hover:bg-slate-100',
            )}
        >
            <CheckCircle2
                className={cn(
                    'size-6 text-slate-400',
                    isSelected && 'text-indigo-500',
                )}
            />

            <div className="flex flex-col gap-1.5">
                <div className="flex items-center gap-2">
                    <p className="text-sm font-bold text-slate-900">
                        {address.fullName}
                    </p>
                    {address.isDefault && (
                        <Badge variant='primary'>
                            デフォルト
                        </Badge>
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
