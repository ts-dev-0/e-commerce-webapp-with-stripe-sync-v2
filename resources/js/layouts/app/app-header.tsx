import DropdownMenuAvatar from '@/components/dropdown-menu-avatar';
import TextLink from '@/components/text-link';
import { Input } from '@/components/ui/input';
import { home, login, register } from '@/routes';
import { orders } from '@/routes/account';
import cart from '@/routes/cart';
import search from '@/routes/search';
import { SharedData } from '@/types';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Package, ShoppingCartIcon } from 'lucide-react';
import React from 'react';

export default function AppHeader() {
    const { auth } = usePage<SharedData>().props;

    return (
        <header className="mx-auto flex h-16 w-full max-w-7xl items-center gap-4 py-4">
            <AppLogo />
            <SearchProductsForm />
            {auth.user ? (
                <>
                    <DropdownMenuAvatar user={auth.user} />
                    <Navigation />
                </>
            ) : (
                <div className="flex items-center gap-2">
                    <TextLink href={login().url} className='text-sm'>Log in</TextLink>
                    <span>/</span>
                    <TextLink href={register().url} className='text-sm'>Sign in</TextLink>
                </div>
            )}
        </header>
    );
}

function AppLogo() {
    return (
        <Link href={home()} className="flex h-full justify-items-center">
            <img src="/logo.svg" alt="app-logo" className="h-8 w-auto" />
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
