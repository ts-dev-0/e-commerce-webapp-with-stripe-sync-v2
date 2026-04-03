import { Link, usePage } from '@inertiajs/react';
import { Heart, MapPin, Settings, ShoppingBag, User } from 'lucide-react';
import { ReactNode } from 'react';
import AppHeader from './app/app-header';

interface AccountLayoutProps {
    children: ReactNode;
}

const menuItems = [
    { title: 'プロフィール', url: '/account/profile', icon: User },
    { title: '注文履歴', url: '/account/orders', icon: ShoppingBag },
    { title: 'アドレス', url: '/account/addresses', icon: MapPin },
    { title: 'お気に入り', url: '/account/favorites', icon: Heart },
    { title: '設定', url: '/account/settings', icon: Settings },
];

export default function AccountLayout({ children }: AccountLayoutProps) {
    const { url } = usePage();

    const isActive = (itemUrl: string) => url.startsWith(itemUrl);

    const profiles = menuItems.map((item) => {
        const Icon = item.icon;
        const active = isActive(item.url);

        return (
            <li key={item.url}>
                <Link
                    href={item.url}
                    className={`flex items-center gap-2 rounded-md px-3 py-2 text-sm transition-colors ${
                        active
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900'
                    }`}
                >
                    <Icon className="h-4 w-4" />
                    <span>{item.title}</span>
                </Link>
            </li>
        );
    });

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

                    <nav className="p-2">
                        <ul className="space-y-1">{profiles}</ul>
                    </nav>
                </aside>

                <main className="min-h-[calc(100vh-5.5rem)] flex-1 bg-slate-50 p-4 sm:p-6 lg:p-8">
                    {children}
                </main>
            </div>
        </div>
    );
}
