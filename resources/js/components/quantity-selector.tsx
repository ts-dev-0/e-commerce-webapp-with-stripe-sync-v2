import { Trash2 } from 'lucide-react';
import ErrorMessage from './error-message';
import { Button } from './ui/button';

interface QuantitySelectorProps {
    quantity: number;
    decrement: () => void;
    increment: () => void;
    onRemove?: () => void;
    min?: number;
    max?: number;
    errorMessage?: string;
}

export function QuantitySelector({
    quantity,
    decrement,
    increment,
    onRemove,
    min = 1,
    max = 10,
    errorMessage,
}: QuantitySelectorProps) {
    const isMin = quantity <= min;
    const isMax = quantity >= max;
    const shouldShowRemoveButton =
        quantity === 1 && typeof onRemove === 'function';

    function handleDecrement() {
        if (isMin) {
            onRemove?.();
            return;
        }

        decrement();
    }

    function handleIncrement() {
        if (isMax) {
            return;
        }

        increment();
    }

    return (
        <div className="flex items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-2 py-1">
            <Button
                type="button"
                variant="outline"
                size="icon"
                className="h-7 w-7 p-0"
                aria-label="数量を減らす"
                onClick={handleDecrement}
                disabled={!shouldShowRemoveButton && isMin}
            >
                {shouldShowRemoveButton ? <Trash2 className="size-3.5" /> : '-'}
                <ErrorMessage message={errorMessage} />
            </Button>
            <span className="text-xs text-slate-700">{quantity}</span>
            <Button
                type="button"
                variant="outline"
                size="icon"
                className="h-7 w-7 p-0"
                aria-label="数量を増やす"
                onClick={handleIncrement}
                disabled={isMax}
            >
                +
            </Button>
        </div>
    );
}
