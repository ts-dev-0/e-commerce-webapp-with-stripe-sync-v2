import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { PREFECTURES } from '@/const/prefectures';
import { update } from '@/routes/addresses';
import { useModalStore } from '@/stores/modalStore';
import { Address, UpdateAddress } from '@/types/address';
import { useForm } from '@inertiajs/react';
import ErrorMessage from './error-message';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Label } from './ui/label';
import { Spinner } from './ui/spinner';

interface EditDeliveryAddressFormProps {
    address: Address;
}

export default function EditDeliveryAddressForm({
    address,
}: EditDeliveryAddressFormProps) {
    const { data, setData, transform, patch, processing, reset, errors } =
        useForm<UpdateAddress>({
            id: address.id,
            fullName: address.fullName,
            postalCode: address.postalCode,
            prefecture: address.prefecture,
            city: address.city,
            addressLine: address.addressLine,
            phoneNumber: address.phoneNumber,
            isDefault: address.isDefault,
        });

    const closeModal = useModalStore((state) => state.closeModal);

    const handleUpdateAddress = () => {
        transform(() => ({
            full_name: data.fullName,
            postal_code: data.postalCode,
            prefecture: data.prefecture,
            city: data.city,
            address_line: data.addressLine,
            phone_number: data.phoneNumber,
            is_default: data.isDefault,
        }));

        patch(update(data.id).url, {
            preserveState: false,
            onSuccess: () => {
                reset();
                closeModal();
            },
        });
    };

    return (
        <>
            <div className="mt-4 grid gap-4 sm:grid-cols-2">
                <div className="flex flex-col gap-y-2">
                    <label className="text-xs font-medium text-slate-600">
                        お名前
                    </label>
                    <Input
                        value={data.fullName}
                        placeholder="山田 太郎"
                        onChange={(e) => setData('fullName', e.target.value)}
                    />
                    <ErrorMessage message={errors.fullName} />
                </div>

                <div className="col-span-2 flex flex-col gap-y-4">
                    <div className="flex flex-col gap-y-2">
                        <label className="text-xs font-medium text-slate-600">
                            郵便番号(半角数字・ハイフンなし)
                        </label>

                        <Input
                            value={data.postalCode}
                            placeholder="0000000"
                            className="w-fit"
                            maxLength={7}
                            onChange={(e) =>
                                setData('postalCode', e.target.value)
                            }
                        />
                        <ErrorMessage message={errors.postalCode} />
                    </div>
                    <div className="flex flex-col gap-y-4">
                        <div className="flex flex-col gap-y-2">
                            <label className="text-xs font-medium text-slate-600">
                                都道府県
                            </label>
                            <Select
                                onValueChange={(value) =>
                                    setData('prefecture', value)
                                }
                                value={data.prefecture}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="都道府県を選択" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        {PREFECTURES.map((prefecture) => (
                                            <SelectItem
                                                key={prefecture.value}
                                                value={prefecture.value}
                                            >
                                                {prefecture.name}
                                            </SelectItem>
                                        ))}
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <ErrorMessage message={errors.prefecture} />
                        </div>
                        <div className="flex flex-col gap-y-2">
                            <label className="text-xs font-medium text-slate-600">
                                市区町村
                            </label>
                            <Input
                                value={data.city}
                                placeholder="○○区"
                                onChange={(e) =>
                                    setData('city', e.target.value)
                                }
                            />
                            <ErrorMessage message={errors.city} />
                        </div>
                        <div className="flex flex-col gap-y-2">
                            <label className="text-xs font-medium text-slate-600">
                                丁目・番地・号 建物名／会社名・部屋番号
                                （数字は半角数字）
                            </label>
                            <Input
                                value={data.addressLine}
                                placeholder="○○町1-2-3 ○○ビル 101号室"
                                onChange={(e) =>
                                    setData('addressLine', e.target.value)
                                }
                            />
                            <ErrorMessage message={errors.addressLine} />
                        </div>
                    </div>
                </div>

                <div className="flex flex-col gap-y-2">
                    <label className="text-xs font-medium text-slate-600">
                        電話番号
                    </label>
                    <Input
                        value={data.phoneNumber}
                        placeholder="09012345678"
                        maxLength={11}
                        onChange={(e) => setData('phoneNumber', e.target.value)}
                    />
                    <ErrorMessage message={errors.phoneNumber} />
                </div>
            </div>
            <div className="mt-4 flex items-center justify-start gap-2">
                <Input
                    checked={data.isDefault}
                    type="checkbox"
                    className="h-4 w-4 border-slate-300 text-emerald-600 accent-emerald-600 focus:ring-emerald-500"
                    onChange={(e) => setData('isDefault', e.target.checked)}
                />
                <Label className="text-sm font-medium text-slate-700">
                    いつもこの住所に届ける
                </Label>
            </div>
            <Button
                className="mt-6 w-full rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"
                variant="default"
                onClick={handleUpdateAddress}
                disabled={processing}
            >
                {processing && <Spinner />}
                変更を保存
            </Button>
        </>
    );
}
