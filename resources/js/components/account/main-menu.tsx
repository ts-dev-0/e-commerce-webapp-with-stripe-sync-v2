import { Link, usePage } from '@inertiajs/react';
import { Lock, MapPin, ShoppingBag, User } from 'lucide-react';

const menuItems = [
    { label: 'プロフィール', url: '/account/profile', icon: User },
    { label: '注文履歴', url: '/account/orders', icon: ShoppingBag },
    { label: 'アドレス', url: '/account/addresses', icon: MapPin },
    { label: 'パスワード変更', url: '/account/settings/password', icon: Lock },
];

export default function MainMenu() {
    const { url } = usePage();

    const isActive = (itemUrl: string) => {
        if (itemUrl === '/account/settings') {
            return url.startsWith('/account/settings');
        }

        return url === itemUrl;
    };
    return (
        <nav className="p-2">
            <ul className="space-y-1">
                {menuItems.map((item) => {
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
                                <span>{item.label}</span>
                            </Link>
                        </li>
                    );
                })}
            </ul>
        </nav>
    );
}
