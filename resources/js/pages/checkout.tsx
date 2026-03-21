import CartItemCard from '@/components/cart-item-card';
// import SavedAddressCard from '@/components/saved-address-card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { PREFECTURES } from '@/const/prefectures';
import { useAddress } from '@/hooks/use-address';
import AppLayout from '@/layouts/app-layout';
import { store } from '@/routes/checkout';
import { Checkout as CheckoutType } from '@/types/checkout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

interface CheckoutProps {
    data: CheckoutType;
}

export default function Checkout({ data }: CheckoutProps) {
    const cartItems = data['cartItems'];
    const subtotal = data['subtotal'];
    const deliveryDateList = data['deliveryDate'];

    const [processing, setProcessing] = useState<boolean>(false);
    const [deliveryDate, setDeliveryDate] = useState<string>(
        deliveryDateList[0],
    );

    const {
        address,
        handleCreateAddress,
        handleFullName,
        handlePostalCode,
        handlePrefecture,
        handleCity,
        handleAddressLine,
        handlePhoneNumber,
        handleIsDefault,
    } = useAddress();

    // TODO: Move logic to back-end
    const shipping = 0;
    const total = subtotal + shipping;

    function handleCheckout() {
        router.post(store().url);
    }

    return (
        <AppLayout>
            <Head title="Checkout" />

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid gap-6 lg:grid-cols-3">
                    <section className="lg:col-span-2">
                        <div className="space-y-6">
                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    登録済み配送先
                                </h2>

                                <div className="mt-4 space-y-4">
                                    <div className="rounded-lg border border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                                        登録済みの配送先がありません。
                                        <div className="mt-2">
                                            新しい配送先を入力してください。
                                        </div>
                                    </div>
                                    {/* TODO: 住所に関する機能の実装に使用する */}
                                    {/* <SavedAddressCard /> */}
                                </div>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    配送先情報
                                </h2>

                                <div className="mt-4 grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label className="text-xs font-medium text-slate-600">
                                            お名前
                                        </label>
                                        <Input
                                            value={address['fullName']}
                                            placeholder="山田 太郎"
                                            onChange={(e) =>
                                                handleFullName(e.target.value)
                                            }
                                        />
                                    </div>

                                    <div className="col-span-2 flex flex-col gap-y-4">
                                        <div>
                                            <label className="text-xs font-medium text-slate-600">
                                                郵便番号(半角数字・ハイフンなし)
                                            </label>
                                            <div className="flex items-center gap-x-2">
                                                <Input
                                                    value={
                                                        address['postalCode']
                                                    }
                                                    placeholder="0000000"
                                                    className="w-fit"
                                                    maxLength={7}
                                                    onChange={(e) =>
                                                        handlePostalCode(
                                                            e.target.value,
                                                        )
                                                    }
                                                />
                                            </div>
                                        </div>
                                        <div className="flex flex-col gap-y-4">
                                            <div>
                                                <label className="text-xs font-medium text-slate-600">
                                                    都道府県
                                                </label>
                                                <Select
                                                    onValueChange={(e) =>
                                                        handlePrefecture(e)
                                                    }
                                                    value={
                                                        address['prefecture']
                                                    }
                                                >
                                                    <SelectTrigger>
                                                        <SelectValue placeholder="都道府県を選択" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectGroup>
                                                            {PREFECTURES.map(
                                                                (
                                                                    prefecture,
                                                                ) => (
                                                                    <SelectItem
                                                                        key={
                                                                            prefecture.value
                                                                        }
                                                                        value={
                                                                            prefecture.value
                                                                        }
                                                                    >
                                                                        {
                                                                            prefecture.name
                                                                        }
                                                                    </SelectItem>
                                                                ),
                                                            )}
                                                        </SelectGroup>
                                                    </SelectContent>
                                                </Select>
                                            </div>
                                            <div>
                                                <label className="text-xs font-medium text-slate-600">
                                                    市区町村
                                                </label>
                                                <Input
                                                    value={address['city']}
                                                    placeholder="○○区"
                                                    onChange={(e) =>
                                                        handleCity(
                                                            e.target.value,
                                                        )
                                                    }
                                                />
                                            </div>
                                            <div>
                                                <label className="text-xs font-medium text-slate-600">
                                                    丁目・番地・号
                                                    建物名／会社名・部屋番号
                                                    （数字は半角数字）
                                                </label>
                                                <Input
                                                    value={
                                                        address['addressLine']
                                                    }
                                                    placeholder="○○町1-2-3 ○○ビル 101号室"
                                                    onChange={(e) =>
                                                        handleAddressLine(
                                                            e.target.value,
                                                        )
                                                    }
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label className="text-xs font-medium text-slate-600">
                                            電話番号
                                        </label>
                                        <Input
                                            value={address['phoneNumber']}
                                            placeholder="09012345678"
                                            maxLength={11}
                                            onChange={(e) =>
                                                handlePhoneNumber(
                                                    e.target.value,
                                                )
                                            }
                                        />
                                    </div>
                                </div>
                                <div className="mt-4 flex items-center justify-start gap-2">
                                    <Input
                                        checked={address['isDefault']}
                                        type="checkbox"
                                        className="h-4 w-4 border-slate-300 text-emerald-600 accent-emerald-600 focus:ring-emerald-500"
                                        onChange={(e) =>
                                            handleIsDefault(e.target.checked)
                                        }
                                    />
                                    <Label className="text-sm font-medium text-slate-700">
                                        いつもこの住所に届ける
                                    </Label>
                                </div>
                                <Button
                                    className="mt-6 w-full rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                                    variant="default"
                                    disabled={processing}
                                    onClick={handleCreateAddress}
                                >
                                    この住所を使用
                                </Button>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    配達希望日時
                                </h2>

                                <div className="mt-4">
                                    <label className="text-xs font-medium text-slate-600">
                                        配達希望日時
                                    </label>
                                    <div className="mt-4 flex flex-col gap-2">
                                        {deliveryDateList.map((date) => (
                                            <div
                                                key={date}
                                                className="flex items-center gap-x-2"
                                            >
                                                <Input
                                                    type="radio"
                                                    id={date}
                                                    name="deliveryDate"
                                                    value={date}
                                                    checked={
                                                        deliveryDate === date
                                                    }
                                                    onChange={(e) =>
                                                        setDeliveryDate(
                                                            e.target.value,
                                                        )
                                                    }
                                                    className="h-4 w-4 accent-emerald-600 focus:ring-emerald-500"
                                                />

                                                <Label
                                                    htmlFor={date}
                                                    className="text-sm font-medium text-slate-700"
                                                >
                                                    {date}
                                                </Label>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    支払い方法
                                </h2>

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
                                    ※ 本実装では決済処理は行われません。今後
                                    Stripe 等と連携します。
                                </p>
                            </div>

                            <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-slate-800">
                                    カート内の商品
                                </h2>

                                <div className="mt-4 space-y-4">
                                    {cartItems.map((item) => {
                                        const product = item['product'];
                                        const quantity = item['quantity'];

                                        return (
                                            <CartItemCard
                                                key={product.id}
                                                product={product}
                                                initialQuantity={quantity}
                                                processing={processing}
                                                setProcessing={setProcessing}
                                            />
                                        );
                                    })}
                                </div>
                            </div>
                        </div>
                    </section>

                    <aside className="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 className="text-lg font-semibold text-slate-800">
                            ご注文内容
                        </h2>

                        <div className="mt-4 space-y-3">
                            {cartItems.map((item) => {
                                const product = item['product'];
                                const quantity = item['quantity'];
                                return (
                                    <div
                                        key={product.id}
                                        className="flex items-start justify-between"
                                    >
                                        <div>
                                            <p className="text-sm font-medium text-slate-800">
                                                {product.name}
                                            </p>
                                            <p className="text-xs text-slate-500">
                                                数量: {quantity}
                                            </p>
                                        </div>
                                        <p className="text-sm font-semibold text-slate-800">
                                            {(
                                                product.price * quantity
                                            ).toLocaleString('ja-JP')}
                                            円
                                        </p>
                                    </div>
                                );
                            })}
                        </div>

                        <div className="mt-6 border-t border-slate-200 pt-4 text-sm text-slate-600">
                            <div className="flex justify-between">
                                <span>小計</span>
                                <span>
                                    {subtotal.toLocaleString('ja-JP')}円
                                </span>
                            </div>
                            <div className="flex justify-between">
                                <span>送料</span>
                                <span>無料</span>
                            </div>
                            <div className="mt-2 flex justify-between text-base font-semibold text-slate-800">
                                <span>合計</span>
                                <span>{total.toLocaleString('ja-JP')}円</span>
                            </div>
                        </div>

                        <Button
                            className="mt-6 w-full rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                            variant="default"
                            disabled={processing}
                            onClick={handleCheckout}
                        >
                            注文を確定する
                        </Button>
                    </aside>
                </div>
            </div>
        </AppLayout>
    );
}
