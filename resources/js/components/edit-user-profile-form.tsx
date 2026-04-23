import ErrorMessage from '@/components/error-message';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { update } from '@/routes/profile';
import { useForm } from '@inertiajs/react';
import { Button } from './ui/button';

interface EditUserProfileForm {
    name: string;
    email: string;
    handleCancel: () => void;
}

export default function EditUserProfileForm({
    name,
    email,
    handleCancel,
}: EditUserProfileForm) {
    const { data, setData, patch, processing, errors } = useForm({
        name: name,
        email: email,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(update().url, {
            onSuccess: () => {
                handleCancel();
            },
        });
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-6">
            <FormField
                id="name"
                label="ユーザー名"
                value={data.name}
                onChange={(e) => setData('name', e.target.value)}
                placeholder="ユーザー名を入力"
                error={errors.name}
            />

            <FormField
                id="email"
                label="メールアドレス"
                type="email"
                value={data.email}
                onChange={(e) => setData('email', e.target.value)}
                placeholder="メールアドレスを入力"
                error={errors.email}
            />

            <div className="flex justify-end gap-3">
                <Button
                    type="button"
                    variant="ghost"
                    onClick={handleCancel}
                    disabled={processing}
                >
                    キャンセル
                </Button>
                <Button type="submit" disabled={processing} variant="primary">
                    {processing ? '保存中...' : '保存'}
                </Button>
            </div>
        </form>
    );
}

interface FormFieldProps {
    id: string;
    label: string;
    type?: string;
    value: string;
    onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
    placeholder?: string;
    error?: string;
}

function FormField({
    id,
    label,
    type = 'text',
    value,
    onChange,
    placeholder,
    error,
}: FormFieldProps) {
    return (
        <div className="flex flex-col gap-2">
            <Label htmlFor={id}>{label}</Label>

            <Input
                id={id}
                type={type}
                value={value}
                onChange={onChange}
                placeholder={placeholder}
            />

            <ErrorMessage message={error} />
        </div>
    );
}
