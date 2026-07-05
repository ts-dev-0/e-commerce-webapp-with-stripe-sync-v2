import { StockStatus as StockStatusType } from '@/types/product';

export function StockStatus({ status, label }: StockStatusType) {
    const colorClass = status === 'inStock' ? 'text-green-600' : 'text-red-600';

    return <span className={`text-sm ${colorClass}`}>{label}</span>;
}
