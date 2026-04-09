import { OrderItem } from './order-item';

type OrderStatus = 'Pending' | 'Paid' | 'Completed' | 'Canceled';

export interface Order {
    orderId: number;
    orderNumber: string;
    status: OrderStatus;
    totalAmount: number;
    orderedAt: string;
    fullName: string;
    postalCode: string;
    prefecture: string;
    city: string;
    addressLine: string;
    phoneNumber: string;
    items: OrderItem[];
}
