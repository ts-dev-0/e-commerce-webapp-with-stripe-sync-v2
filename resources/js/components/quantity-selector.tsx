import { Minus, Plus, Trash2 } from 'lucide-react';
import { Button } from './ui/button';

interface QuantitySelectorProps {
    quantity: number;
    onChange: (newQuantity: number) => void;
    showTrashIcon: boolean;
    processing: boolean;
}

export function QuantitySelector({
    quantity,
    onChange,
    showTrashIcon,
    processing,
}: QuantitySelectorProps) {
    const min = quantity <= 1;
    const max = quantity >= 10;

    return (
        <div className="flex w-fit items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-2 py-1">
            <Button
                variant="outline"
                size="icon"
                className="size-7"
                aria-label="数量を減らす"
                onClick={() => onChange(quantity - 1)}
                disabled={(!showTrashIcon && min) || processing}
            >
                {showTrashIcon && min ? (
                    <Trash2 className="size-3.5" />
                ) : (
                    <Minus className='size-3.5'/>
                )}
            </Button>
            <span className="text-xs text-slate-700">{quantity}</span>
            <Button
                variant="outline"
                size="icon"
                className="size-7"
                aria-label="数量を増やす"
                onClick={() => onChange(quantity + 1)}
                disabled={max || processing}
            >
                <Plus className='size-3.5'/>
            </Button>
        </div>
    );
}
