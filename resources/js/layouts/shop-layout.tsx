import { Head } from '@inertiajs/react';
import { ReactNode } from 'react';

interface Props {
    children: ReactNode;
    title?: string;
}

export function ShopLayout({ children, title }: Props) {
    return (
        <>
            <Head title={title} />
            <div className="mx-auto max-w-7xl">{children}</div>
        </>
    );
}
