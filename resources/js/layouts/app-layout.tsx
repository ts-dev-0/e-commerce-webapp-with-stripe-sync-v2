import { ReactNode } from 'react';
import AppHeader from './app/app-header';

interface AppLayoutProps {
    children: ReactNode;
}

export default function AppLayout({ children }: AppLayoutProps) {
    return (
        <div className="flex min-h-dvh flex-col">
            <AppHeader />

            <main className="flex-1">{children}</main>
        </div>
    );
}
