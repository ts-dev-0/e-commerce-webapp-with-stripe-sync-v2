import MainMenu from '@/components/account/main-menu';
import { ReactNode } from 'react';
import AppHeader from './app/app-header';

interface AccountLayoutProps {
    children: ReactNode;
}

export default function AccountLayout({ children }: AccountLayoutProps) {
    return (
        <div className="flex min-h-svh flex-col gap-y-5 bg-slate-50">
            <AppHeader />

            <div className="mx-auto flex w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <aside className="sticky top-16 min-h-[calc(100vh-5.5rem)] w-64 shrink-0 border border-slate-200 bg-white shadow-sm">
                    <div className="border-b border-slate-200 px-3 py-4">
                        <p className="text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            アカウント
                        </p>
                        <p className="text-sm font-bold text-slate-900">
                            メニュー
                        </p>
                    </div>

                    <MainMenu />
                </aside>

                <main className="min-h-[calc(100vh-5.5rem)] flex-1 bg-slate-50 p-4 sm:p-6 lg:p-8">
                    {children}
                </main>
            </div>
        </div>
    );
}
