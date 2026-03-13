import { ReactNode } from 'react';

type Props = {
    title: string;
    description: string;
    children: ReactNode;
};

export default function AuthLayout({ title, description, children }: Props) {
    return (
        <div className="flex min-h-svh flex-col items-center justify-center gap-y-8 bg-slate-100">
            <div className="mx-auto flex w-full max-w-11/12 flex-col space-y-5 pt-20 text-center">
                <h1 className="text-xl font-medium">{title}</h1>
                <p className="text-sm">{description}</p>
            </div>
            {children}
        </div>
    );
}
