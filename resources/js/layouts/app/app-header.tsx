import { home } from '@/routes';
import cart from '@/routes/cart';
import { Link } from '@inertiajs/react';

export default function AppHeader() {
    return (
        <header>
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="flex h-16 items-center justify-between">
                    <div className="flex items-center">
                        <Link
                            href={home()}
                            className="text-xl font-semibold text-slate-800"
                        >
                            EC-Site V2
                        </Link>
                    </div>

                    <nav className="hidden space-x-4 md:flex">
                        <Link
                            href={home()}
                            className="text-slate-700 hover:text-slate-900"
                        >
                            Home
                        </Link>

                        <Link
                            href={cart.index()}
                            className="text-slate-700 hover:text-slate-900"
                        >
                            Cart
                        </Link>
                        <Link
                            href="#"
                            className="text-slate-700 hover:text-slate-900"
                        >
                            Account
                        </Link>
                    </nav>

                    {/* TODO:Backend側の処理を見直してからFront側の作成の続きを行う */}
                    <div className="flex items-center space-x-4">
                        <input
                            type="search"
                            placeholder="Search"
                            className="rounded-md border px-2 py-1 text-sm focus:outline-none"
                        />
                    </div>
                </div>
            </div>
        </header>
    );
}
