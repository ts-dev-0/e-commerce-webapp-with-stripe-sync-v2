import { Address } from './address';
import { CartItem } from './cart-item';

export interface Checkout {
    cartItems: CartItem[];
    subtotal: number;
    deliveryDate: string[];
    addresses: Address[];
    shippingFee: number;
    total: number;
}
