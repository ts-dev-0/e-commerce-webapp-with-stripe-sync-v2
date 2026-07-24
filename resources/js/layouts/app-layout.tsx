import { ReactNode } from 'react';
import AppHeader from './app/app-header';

interface AppLayoutProps {
    children: ReactNode;
}

export default function AppLayout({ children }: AppLayoutProps) {
    return (
        <div className="flex min-h-dvh flex-col">
            <AppHeader />

            <main className='flex-1'>{children}</main>
        </div>
    );
}

// Shop layout
// 〇 shop/index 
// 〇 shop/product-detail
// 〇 shop/product-search
// 〇 cart/index
// 〇 checkout/index
// 〇 checkout/success
// 〇 checkout/failed

// Account layout
// 〇 account/index
// 〇 account/address/index
// 〇 account/order/index
// 〇 account/security/index