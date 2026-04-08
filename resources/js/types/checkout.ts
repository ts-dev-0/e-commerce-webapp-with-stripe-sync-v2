import { Address } from './address';
import { CartItem } from './cart-item';

export interface Checkout {
    cartItems: CartItem[];
    defaultAddress: Address;
    anotherAddresses: Address[];
    deliveryDate: string[];
    shippingFee: number;
    subtotal: number;
    total: number;
}
