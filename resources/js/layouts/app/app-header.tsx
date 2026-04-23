import { Input } from '@/components/ui/input';
import { UserInfo } from '@/components/user-info';
import { home } from '@/routes';
import account, { orders } from '@/routes/account';
import cart from '@/routes/cart';
import search from '@/routes/search';
import { SharedData } from '@/types';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Package, ShoppingCartIcon } from 'lucide-react';
import React from 'react';

export default function AppHeader() {
    const { auth } = usePage<SharedData>().props;

    return (
        <header className="mx-auto flex w-full max-w-7xl items-center gap-4 py-4">
            <AppLogo />
            <SearchProductsForm />
            <Link href={account.index().url}>
                <UserInfo user={auth.user} />
            </Link>
            <Navigation />
        </header>
    );
}

function AppLogo() {
    return (
        <Link href={home()}>
            <img src="/logo.svg" alt="app-logo" />
        </Link>
    );
}

function Navigation() {
    return (
        <nav className="flex flex-1 items-center gap-4">
            <NavItem
                href={cart.index().url}
                label="カート"
                Icon={ShoppingCartIcon}
            />
            <NavItem href={orders().url} label="注文履歴" Icon={Package} />
        </nav>
    );
}

interface NavItemProps {
    href: string;
    label: string;
    Icon: React.ComponentType;
}

function NavItem({ href, label, Icon }: NavItemProps) {
    return (
        <Link href={href} className="group flex w-fit flex-col items-center">
            <Icon />
            <span className="text-xs group-hover:underline">{label}</span>
        </Link>
    );
}

interface SearchProductsForm {
    keyword: string;
}

function SearchProductsForm() {
    const { data, setData, get, reset } = useForm<SearchProductsForm>({
        keyword: '',
    });

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

        if (data.keyword.length === 0) return;
        get(search.products().url, {
            onSuccess: () => {
                reset();
            },
        });
    }

    return (
        <div className="w-full max-w-xl">
            <form onSubmit={handleSubmit}>
                <Input
                    type="search"
                    placeholder="キーワード検索"
                    value={data.keyword}
                    onChange={(e) => setData({ keyword: e.target.value })}
                />
            </form>
        </div>
    );
}
