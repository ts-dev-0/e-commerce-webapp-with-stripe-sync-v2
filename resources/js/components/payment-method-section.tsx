import { Input } from './ui/input';

export function PaymentMethodSection() {
    return (
        <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-slate-800">支払い方法</h2>

            <div className="mt-4 space-y-4">
                <div className="flex items-center gap-3">
                    <Input
                        type="radio"
                        name="payment"
                        id="payment-stripe"
                        defaultChecked
                        className="h-4 w-4 accent-emerald-600 focus:ring-emerald-500"
                    />
                    <label
                        htmlFor="payment-stripe"
                        className="text-sm font-medium text-slate-700"
                    >
                        Stripe（準備中）
                    </label>
                </div>
            </div>

            <p className="mt-4 text-xs text-slate-500">
                ※ 本実装では決済処理は行われません。今後 Stripe 等と連携します。
            </p>
        </div>
    );
}
