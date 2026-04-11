interface StockStatus {
    status: 'inStock' | 'lowStock' | 'outOfStock';
    label: string;
}

export interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    manufacturer: string;
    stockStatus: StockStatus;
}
