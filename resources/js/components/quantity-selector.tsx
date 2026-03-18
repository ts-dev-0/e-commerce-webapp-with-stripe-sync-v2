import { Trash2 } from 'lucide-react';
import { Button } from './ui/button';

interface QuantitySelectorProps {
    decrement: () => void;
    increment: () => void;
    quantity: number;
    onRemove?: () => void;
}

export function QuantitySelector({
    decrement,
    increment,
    quantity,
    onRemove,
}: QuantitySelectorProps) {
    const isMin = quantity <= 1;

    function handleOnClick() {
        if (isMin && onRemove) {
            onRemove();
            return;
        }

        decrement();
    }
    return (
        <div className="flex items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-2 py-1">
            <Button
                type="button"
                variant="outline"
                size="icon"
                className="h-7 w-7 p-0"
                aria-label="数量を減らす"
                onClick={handleOnClick}
            >
                {isMin && onRemove ? <Trash2 className="size-3.5" /> : '-'}
            </Button>
            <span className="text-xs text-slate-700">{quantity}</span>
            <Button
                type="button"
                variant="outline"
                size="icon"
                className="h-7 w-7 p-0"
                aria-label="数量を増やす"
                onClick={increment}
            >
                +
            </Button>
        </div>
    );
}
