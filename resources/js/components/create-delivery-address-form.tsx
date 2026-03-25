import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { PREFECTURES } from '@/const/prefectures';
import { store } from '@/routes/addresses';
import { useModalStore } from '@/stores/modalStore';
import { CreateAddress } from '@/types/address';
import { useForm } from '@inertiajs/react';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Label } from './ui/label';

export default function CreateDeliveryAddressForm() {
    const { data, setData, transform, post, processing, reset } =
        useForm<CreateAddress>({
            fullName: '',
            postalCode: '',
            prefecture: '東京都',
            city: '',
            addressLine: '',
            phoneNumber: '',
            isDefault: false,
        });

    const closeModal = useModalStore((state) => state.closeModal);

    const handleCreateAddress = () => {
        transform(() => ({
            full_name: data.fullName,
            postal_code: data.postalCode,
            prefecture: data.prefecture,
            city: data.city,
            address_line: data.addressLine,
            phone_number: data.phoneNumber,
            is_default: data.isDefault,
        }));

        post(store().url, {
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
                <div>
                    <label className="text-xs font-medium text-slate-600">
                        お名前
                    </label>
                    <Input
                        value={data['fullName']}
                        placeholder="山田 太郎"
                        onChange={(e) => setData('fullName', e.target.value)}
                    />
                </div>

                <div className="col-span-2 flex flex-col gap-y-4">
                    <div>
                        <label className="text-xs font-medium text-slate-600">
                            郵便番号(半角数字・ハイフンなし)
                        </label>
                        <div className="flex items-center gap-x-2">
                            <Input
                                value={data['postalCode']}
                                placeholder="0000000"
                                className="w-fit"
                                maxLength={7}
                                onChange={(e) =>
                                    setData('postalCode', e.target.value)
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
                                onValueChange={(e) => setData('prefecture', e)}
                                value={data['prefecture']}
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
                        </div>
                        <div>
                            <label className="text-xs font-medium text-slate-600">
                                市区町村
                            </label>
                            <Input
                                value={data['city']}
                                placeholder="○○区"
                                onChange={(e) =>
                                    setData('city', e.target.value)
                                }
                            />
                        </div>
                        <div>
                            <label className="text-xs font-medium text-slate-600">
                                丁目・番地・号 建物名／会社名・部屋番号
                                （数字は半角数字）
                            </label>
                            <Input
                                value={data['addressLine']}
                                placeholder="○○町1-2-3 ○○ビル 101号室"
                                onChange={(e) =>
                                    setData('addressLine', e.target.value)
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
                        value={data['phoneNumber']}
                        placeholder="09012345678"
                        maxLength={11}
                        onChange={(e) => setData('phoneNumber', e.target.value)}
                    />
                </div>
            </div>
            <div className="mt-4 flex items-center justify-start gap-2">
                <Input
                    checked={data['isDefault']}
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
                onClick={handleCreateAddress}
                disabled={processing}
            >
                この住所を使用
            </Button>
        </>
    );
}
