import { Button } from '@/components/ui/button';
import { home } from '@/routes';
import account from '@/routes/account';
import { Link } from '@inertiajs/react';
import { CheckCircle2 } from 'lucide-react';

interface CancelOrderCompleteProps {
    orderNumber: number;
}

export default function Success({ orderNumber }: CancelOrderCompleteProps) {
    return (
        <div className="mx-auto h-screen max-h-screen w-5xl">
            <div className="flex flex-col items-center gap-6 pt-32">
                <div className="flex items-center gap-3">
                    <CheckCircle2 className="size-20 text-indigo-600/80" />
                    <h1 className="text-2xl font-semibold">
                        ご注文のキャンセルが完了しました
                    </h1>
                </div>
                <div>
                    <p className="text-lg font-semibold">
                        ご注文番号: {orderNumber}{' '}
                        のキャンセル処理が正常に完了しました。
                    </p>
                </div>
                <div className="flex gap-3">
                    <Button asChild variant={'primary'}>
                        <Link href={account.orders().url}>注文履歴を見る</Link>
                    </Button>

                    <Button asChild variant={'outline'}>
                        <Link href={home().url}>トップページへ戻る</Link>
                    </Button>
                </div>
            </div>
        </div>
    );
}
