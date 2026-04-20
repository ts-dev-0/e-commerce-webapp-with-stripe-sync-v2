import { SearchProductsForm } from '@/components/search-products-form';
import { home } from '@/routes';
import account from '@/routes/account';
import cart from '@/routes/cart';
import { Link } from '@inertiajs/react';

export default function AppHeader() {
    return (
        <header className="pb-2">
            <Main />
            <Sub />
        </header>
    );
}

function Main() {
    return (
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div className="flex h-16 items-center justify-between">
                <div className="flex items-center">
                    <Link
                        href={home()}
                        className="text-xl font-semibold text-slate-800"
                    >
                        <img src="/logo.svg" alt="app-logo" />
                    </Link>
                </div>

                <SearchProductsForm />
            </div>
        </div>
    );
}

function Sub() {
    return (
        <nav className="mx-auto hidden max-w-7xl space-x-4 sm:px-6 md:flex lg:px-8">
            <SubItem href={home().url} label="ホーム" />
            <SubItem href={cart.index().url} label="カート" />
            <SubItem href={account.index().url} label="アカウント" />
        </nav>
    );
}

interface SubItemProps {
    href: string;
    label: string;
}

function SubItem({ href, label }: SubItemProps) {
    return (
        <Link
            href={href}
            className="text-slate-700 hover:text-slate-900 hover:underline hover:underline-offset-2"
        >
            {label}
        </Link>
    );
}
