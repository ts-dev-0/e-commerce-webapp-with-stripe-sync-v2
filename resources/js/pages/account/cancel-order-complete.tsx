import { Button } from '@/components/ui/button';
import { home } from '@/routes';
import account from '@/routes/account';
import { Link } from '@inertiajs/react';
import { CheckCircle2 } from 'lucide-react';

interface CancelOrderCompleteProps {
    orderNumber: number;
}

export default function CancelOrderComplete({
    orderNumber,
}: CancelOrderCompleteProps) {
    return (
        <div className="mx-auto mt-20 max-w-lg text-center">
            <div className="mb-6 flex justify-center">
                <div className="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                    <CheckCircle2 className="text-emerald-600" />
                </div>
            </div>

            <h1 className="mb-2 text-2xl font-semibold">
                注文をキャンセルしました
            </h1>

            <p className="mb-4 text-gray-500">注文番号: {orderNumber}</p>
            <p className="mb-6 text-gray-600">
                ご注文のキャンセルが正常に完了しました。
            </p>

            <div className="space-x-4">
                <Button asChild variant={'primary'}>
                    <Link href={account.orders().url}>注文履歴を見る</Link>
                </Button>

                <Button asChild variant={'outline'}>
                    <Link href={home().url}>トップページへ戻る</Link>
                </Button>
            </div>
        </div>
    );
}
