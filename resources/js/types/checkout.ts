import { CartItem } from './cart-item';

export interface Checkout {
    items: CartItem[];
    subtotal: number;
}
