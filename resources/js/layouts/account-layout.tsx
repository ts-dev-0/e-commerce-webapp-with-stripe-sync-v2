import { Head } from '@inertiajs/react';
import { ReactNode } from 'react';

interface AccountLayoutProps {
    title: string;
    children: ReactNode;
}

export default function AccountLayout({ title, children }: AccountLayoutProps) {
    return (
        <>
            <Head title={title} />
            <div className="mx-auto max-w-7xl">{children}</div>
        </>
    );
}
