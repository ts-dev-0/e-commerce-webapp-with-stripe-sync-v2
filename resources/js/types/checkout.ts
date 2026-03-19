import { CartItem } from './cart-item';

export interface Checkout {
    cartItems: CartItem[];
    subtotal: number;
    deliveryDate: string[];
}
