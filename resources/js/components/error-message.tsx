import { cn } from '@/lib/utils';
import { AlertCircle } from 'lucide-react';

type ErrorMessageProps = {
    message?: string;
    className?: string;
};

export default function ErrorMessage({
    message,
    className,
}: ErrorMessageProps) {
    if (!message) return null;

    return (
        <div
            role="alert"
            className={cn(
                'mt-2 flex w-fit items-start gap-2 rounded-md border border-rose-200 bg-rose-50 px-3 py-2',
                className,
            )}
        >
            <AlertCircle className="mt-0.5 h-4 w-4 shrink-0 text-rose-500" />

            <p className="text-sm text-rose-700">{message}</p>
        </div>
    );
}
