import { useState } from 'react';
import { Button } from './ui/button';

interface SavedAddressCardProps {
    id: number;
    name: string;
    postal: number;
    address: string;
    phone: string;
}

export default function SavedAddressCard({
    id,
    name,
    postal,
    address,
    phone,
}: SavedAddressCardProps) {
    const [isSelected, setSelectedAddressId] = useState<number>(id);
    return (
        <Button
            type="button"
            onClick={() => setSelectedAddressId(id)}
            className={
                'flex w-full items-start justify-between rounded-lg border p-4 text-left ' +
                (isSelected
                    ? 'border-emerald-500 bg-emerald-50'
                    : 'border-slate-200 bg-slate-50 hover:bg-slate-100')
            }
        >
            <div>
                <p className="text-sm font-semibold text-slate-800">{name}</p>
                <p className="text-xs text-slate-600">
                    {postal} {address}
                </p>
                <p className="text-xs text-slate-600">{phone}</p>
            </div>

            <div className="flex h-8 w-8 items-center justify-center rounded-full border text-sm font-semibold">
                {isSelected ? '✓' : ''}
            </div>
        </Button>
    );
}
