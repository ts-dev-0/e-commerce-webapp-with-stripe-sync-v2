import { ReactNode } from 'react';
import AppHeader from './app/app-header';

interface AccountLayoutProps {
    title: string;
    description?: string;
    children: ReactNode;
}

export default function AccountLayout({
    title,
    description,
    children,
}: AccountLayoutProps) {
    return (
        <div className="flex min-h-svh flex-col gap-y-5">
            <AppHeader />

            <div className="mx-auto flex w-full max-w-7xl flex-col px-4 sm:px-6 lg:px-8">
                <main className="mx-auto w-full max-w-4xl p-4">
                    <div className="my-6 space-y-2">
                        <h1 className="text-2xl font-semibold">{title}</h1>
                        <p className="text-sm text-slate-600">{description}</p>
                    </div>
                    {children}
                </main>
            </div>
        </div>
    );
}
