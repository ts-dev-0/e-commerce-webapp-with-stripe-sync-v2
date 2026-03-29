import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { home } from '@/routes';
import { Link } from '@inertiajs/react';
import { CheckCircle2 } from 'lucide-react';

export default function Success() {
    return (
        <AppLayout>
            <div className="mx-auto mt-12 max-w-xl rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
                <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-full">
                    <CheckCircle2 className="text-emerald-600" size={50} />
                </div>

                <h1 className="mt-6 text-xl font-semibold text-slate-800">
                    ご購入が完了しました
                </h1>

                <p className="mt-3 text-sm text-slate-500">
                    ご注文ありがとうございます。注文内容はメールでも送信されています。
                </p>

                <div className="mt-6 flex justify-center gap-3">
                    <Button
                        asChild
                        className="bg-emerald-600 hover:bg-emerald-700"
                    >
                        <Link href={home()}>ホームへ戻る</Link>
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
}
