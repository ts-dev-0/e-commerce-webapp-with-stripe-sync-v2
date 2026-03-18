import { useState } from 'react';

interface Props {
    initialQuantity: number;
    min?: number;
    max?: number;
}

export function useLocalQuantity({
    initialQuantity,
    min = 1,
    max = 10,
}: Props) {
    const [quantity, setQuantity] = useState<number>(initialQuantity);

    function decrement() {
        if (quantity <= min) return;

        setQuantity((prev) => prev - 1);
    }

    function increment() {
        if (quantity >= max) return;

        setQuantity((prev) => prev + 1);
    }

    return {
        quantity,
        decrement,
        increment,
    };
}
