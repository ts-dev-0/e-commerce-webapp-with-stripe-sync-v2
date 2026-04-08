import { Address } from './address';
import { CartItem } from './cart-item';

export interface Checkout {
    cartItems: CartItem[];
    addresses: Address[];
    defaultAddress: Address;
    anotherAddresses: Address[];
    deliveryDate: string[];
    shippingFee: number;
    subtotal: number;
    total: number;
}
