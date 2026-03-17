import { Button } from './ui/button';

interface QuantitySelectorProps {
    value: number;
    onChange: (prev: number) => void;
    min?: number;
    max?: number;
}

export function QuantitySelector({
    value,
    onChange,
    min = 1,
    max = 10,
}: QuantitySelectorProps) {
    const handleMinus = () => {
        if (value <= min) return;
        onChange(value - 1);
    };

    const handlePlus = () => {
        if (value >= max) return;
        onChange(value + 1);
    };

    return (
        <div className="flex items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-2 py-1">
            <Button
                type="button"
                variant="outline"
                size="icon"
                className="h-7 w-7 p-0"
                aria-label="数量を減らす"
                onClick={() => handleMinus()}
            >
                -
            </Button>
            <span className="text-xs text-slate-700">{value}</span>
            <Button
                type="button"
                variant="outline"
                size="icon"
                className="h-7 w-7 p-0"
                aria-label="数量を増やす"
                onClick={() => handlePlus()}
            >
                +
            </Button>
        </div>
    );
}
