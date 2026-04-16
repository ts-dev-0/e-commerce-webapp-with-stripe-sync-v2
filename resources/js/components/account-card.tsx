import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Link } from '@inertiajs/react';
import { ArrowRight, type LucideIcon } from 'lucide-react';

interface AccountCardProps {
    title: string;
    description: string;
    body: string;
    href: string;
    actionLabel: string;
    icon: LucideIcon;
}

export default function AccountCard({
    title,
    description,
    body,
    href,
    actionLabel,
    icon: Icon,
}: AccountCardProps) {
    return (
        <Card className="flex flex-col transition-shadow hover:shadow-lg">
            <CardHeader>
                <div className="flex items-center justify-between">
                    <CardTitle className="flex items-center gap-2 text-lg text-slate-900">
                        <Icon className="h-5 w-5 text-slate-900" />
                        {title}
                    </CardTitle>
                </div>
                <CardDescription>{description}</CardDescription>
            </CardHeader>
            <CardContent className="flex flex-1 flex-col">
                <p className="mb-4 text-sm text-slate-600">{body}</p>
                <div className="mt-auto">
                    <Button
                        asChild
                        variant="ghost"
                        className="w-full justify-between text-slate-900 hover:bg-slate-50 hover:text-slate-900"
                    >
                        <Link href={href}>
                            {actionLabel}
                            <ArrowRight className="h-4 w-4" />
                        </Link>
                    </Button>
                </div>
            </CardContent>
        </Card>
    );
}
