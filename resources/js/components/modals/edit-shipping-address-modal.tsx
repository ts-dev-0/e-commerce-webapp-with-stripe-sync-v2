import { PREFECTURES } from '@/const/prefectures';
import { update } from '@/routes/addresses';
import { useModalStore } from '@/stores/modalStore';
import { Address, UpdateAddress } from '@/types/address';
import { useForm } from '@inertiajs/react';
import { XIcon } from 'lucide-react';
import ErrorMessage from '../error-message';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '../ui/select';
import { Spinner } from '../ui/spinner';
import ModalWrapper from './modal-wrapper';

interface Props {
    address: Address;
}

export default function EditShippingAddressModal({ address }: Props) {
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
        <ModalWrapper>
            <div className="flex items-center justify-between">
                <h2 className="text-lg font-semibold">配送先住所を編集</h2>
                <button
                    type="button"
                    className="rounded-md p-2 text-slate-500 hover:bg-slate-100"
                    onClick={closeModal}
                >
                    <XIcon />
                </button>
            </div>

            <div className="mt-4 grid gap-4 sm:grid-cols-2">
                <FormInputField
                    label="お名前"
                    value={data.fullName}
                    placeholder="山田 太郎"
                    onChange={(e) => setData('fullName', e.target.value)}
                    errorMessage={errors.fullName}
                />
                <div className="col-span-2 flex flex-col gap-y-4">
                    <FormInputField
                        label="郵便番号(半角数字・ハイフンなし)"
                        value={data.postalCode}
                        placeholder="1234567"
                        onChange={(e) => setData('postalCode', e.target.value)}
                        errorMessage={errors.postalCode}
                    />
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
                        <FormInputField
                            label="市区町村"
                            value={data.city}
                            placeholder="○○区"
                            onChange={(e) => setData('city', e.target.value)}
                            errorMessage={errors.city}
                        />
                        <FormInputField
                            label="丁目・番地・号 建物名／会社名・部屋番号
                                （数字は半角数字）"
                            value={data.addressLine}
                            placeholder="○○町1-2-3 ○○ビル 101号室"
                            onChange={(e) =>
                                setData('addressLine', e.target.value)
                            }
                            errorMessage={errors.addressLine}
                        />
                    </div>
                </div>

                <FormInputField
                    label="電話番号"
                    value={data.phoneNumber}
                    placeholder="09012345678"
                    maxLength={11}
                    onChange={(e) => setData('phoneNumber', e.target.value)}
                    errorMessage={errors.phoneNumber}
                />
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
        </ModalWrapper>
    );
}

interface FormInputFieldProps extends React.InputHTMLAttributes<HTMLInputElement> {
    label: string;
    errorMessage?: string;
}

function FormInputField({
    label,
    errorMessage,
    ...props
}: FormInputFieldProps) {
    return (
        <div className="flex flex-col gap-y-2">
            <label className="text-xs font-medium text-slate-600">
                {label}
            </label>

            <Input {...props} />

            <ErrorMessage message={errorMessage} />
        </div>
    );
}
