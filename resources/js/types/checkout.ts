import { Address } from './address';
import { CartItem } from './cart-item';

export interface Checkout {
    cartItems: CartItem[];
    addresses: Address[];
    shippingFee: number;
    subtotal: number;
    total: number;
}
