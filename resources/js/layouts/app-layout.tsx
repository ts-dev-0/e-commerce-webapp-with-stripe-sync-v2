import { ReactNode } from 'react';
import AppHeader from './app/app-header';

interface AppLayoutProps {
    children: ReactNode;
}

export default function AppLayout({ children }: AppLayoutProps) {
    return (
        <div className="flex min-h-svh flex-col gap-y-5">
            <AppHeader />

            <div className="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                {children}
            </div>
        </div>
    );
}
