import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import cart from '@/routes/cart';
import { Link } from '@inertiajs/react';
import { XCircle } from 'lucide-react';

export default function Failed() {
    return (
        <AppLayout>
            <div className="mx-auto mt-12 max-w-xl rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
                <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-full">
                    <XCircle className="text-red-600" size={50} />
                </div>

                <h1 className="mt-6 text-xl font-semibold text-slate-800">
                    ご購入に失敗しました
                </h1>

                <p className="mt-3 text-sm text-slate-500">
                    決済処理が完了できませんでした。もう一度お試しください。
                </p>

                <div className="mt-6 flex justify-center gap-3">
                    <Button
                        asChild
                        className="bg-emerald-600 hover:bg-emerald-700"
                    >
                        <Link href={cart.index()}>カートへ戻る</Link>
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
}
