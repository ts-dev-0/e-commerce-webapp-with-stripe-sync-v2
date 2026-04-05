import { OrderItem } from './order-item';

type OrderStatus = 'Pending' | 'Paid' | 'Completed' | 'Canceled';

export interface Order {
    orderId: number;
    status: OrderStatus;
    totalAmount: number;
    orderedAt: string;
    items: OrderItem[];
}
