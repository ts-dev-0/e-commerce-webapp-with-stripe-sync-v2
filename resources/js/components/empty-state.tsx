import { ReactNode } from 'react';

interface EmptyStateProps {
    title: string;
    description?: string;
    icon?: ReactNode;
    action?: ReactNode;
    className?: string;
}

export function EmptyState({
    title,
    description,
    icon,
    action,
    className = '',
}: EmptyStateProps) {
    return (
        <div
            className={`rounded-lg border border-slate-200 bg-slate-50 p-8 text-center ${className}`}
        >
            {icon && (
                <div className="mb-4 flex justify-center text-slate-400">
                    {icon}
                </div>
            )}

            <h3 className="text-sm font-medium text-slate-900">{title}</h3>

            {description && (
                <p className="mt-2 text-sm text-slate-500">{description}</p>
            )}

            {action && <div className="mt-6">{action}</div>}
        </div>
    );
}
