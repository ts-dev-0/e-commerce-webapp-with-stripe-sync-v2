import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';

export function PaymentMethodSection() {
    return (
        <div className="flex flex-col gap-3">
            <h2 className="text-lg font-semibold text-slate-800">支払い方法</h2>

            <RadioGroup defaultValue="stripe">
                <div className="flex items-center gap-3">
                    <RadioGroupItem id="payment-stripe" value="stripe" />
                    <Label
                        htmlFor="payment-stripe"
                        className="text-sm font-medium text-slate-700"
                    >
                        Stripe
                    </Label>
                </div>
            </RadioGroup>

            <p className="text-xs leading-relaxed text-slate-500">
                ※ ボタンクリック後、Stripe
                のテスト決済画面へ遷移します（実際の請求は発生しません）。
                <br />
                カード番号欄に「
                <span className="rounded bg-slate-100 px-1 font-mono text-slate-700">
                    4242 4242 4242 4242
                </span>
                」を入力し、有効期限やCVC、名前などは任意のダミー情報で決済をお試しいただけます。
            </p>
        </div>
    );
}
