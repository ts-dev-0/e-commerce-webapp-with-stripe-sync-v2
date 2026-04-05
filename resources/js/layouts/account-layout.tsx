import { ReactNode } from 'react';
import AppHeader from './app/app-header';

interface AccountLayoutProps {
    children: ReactNode;
}

export default function AccountLayout({ children }: AccountLayoutProps) {
    return (
        <div className="flex min-h-svh flex-col gap-y-5 bg-slate-50">
            <AppHeader />

            <div className="mx-auto flex w-full max-w-7xl flex-col px-4 sm:px-6 lg:px-8">
                <h1 className="text-2xl font-semibold">アカウント</h1>

                <main className="bg-slate-500 p-4">
                    {children}
                </main>
            </div>
        </div>
    );
}
