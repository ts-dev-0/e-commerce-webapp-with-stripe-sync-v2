import ErrorMessage from '@/components/error-message';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { update } from '@/routes/password';
import { useForm } from '@inertiajs/react';
import { useState } from 'react';

interface EditPasswordFormProps {
    handleCancel: () => void;
}

export default function EditPasswordForm({
    handleCancel,
}: EditPasswordFormProps) {
    const { data, setData, put, processing, errors, reset } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const [showCurrentPassword, setShowCurrentPassword] = useState(false);
    const [showNewPassword, setShowNewPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(update().url, {
            onSuccess: () => {
                reset();
                alert('パスワードが変更されました。');
            },
        });
    };
    return (
        <form onSubmit={handleSubmit} className="space-y-6">
            <PasswordField
                id="current_password"
                label="現在のパスワード"
                value={data.current_password}
                placeholder="現在のパスワードを入力"
                show={showCurrentPassword}
                toggle={() => setShowCurrentPassword(!showCurrentPassword)}
                onChange={(value) => setData('current_password', value)}
                error={errors.current_password}
            />

            <PasswordField
                id="password"
                label="新しいパスワード"
                value={data.password}
                placeholder="新しいパスワードを入力"
                show={showNewPassword}
                toggle={() => setShowNewPassword(!showNewPassword)}
                onChange={(value) => setData('password', value)}
                error={errors.password}
            />

            <PasswordField
                id="password_confirmation"
                label="パスワード確認"
                value={data.password_confirmation}
                placeholder="新しいパスワードを再入力"
                show={showConfirmPassword}
                toggle={() => setShowConfirmPassword(!showConfirmPassword)}
                onChange={(value) => setData('password_confirmation', value)}
                error={errors.password_confirmation}
            />

            <div className="flex justify-end gap-3">
                <Button
                    type="button"
                    variant="outline"
                    onClick={handleCancel}
                    disabled={processing}
                >
                    キャンセル
                </Button>
                <Button type="submit" disabled={processing} variant={'primary'}>
                    {processing ? '変更中...' : 'パスワードを変更'}
                </Button>
            </div>
        </form>
    );
}

type PasswordFieldProps = {
    id: string;
    label: string;
    value: string;
    placeholder: string;
    show: boolean;
    toggle: () => void;
    onChange: (value: string) => void;
    error?: string;
};

function PasswordField({
    id,
    label,
    value,
    placeholder,
    show,
    toggle,
    onChange,
    error,
}: PasswordFieldProps) {
    return (
        <div className="flex flex-col gap-2">
            <Label htmlFor={id}>{label}</Label>

            <div className="relative">
                <Input
                    id={id}
                    type={show ? 'text' : 'password'}
                    value={value}
                    onChange={(e) => onChange(e.target.value)}
                    className="pr-12"
                    placeholder={placeholder}
                />

                <button
                    type="button"
                    className="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-slate-500"
                    onClick={toggle}
                >
                    {show ? '隠す' : '表示'}
                </button>
            </div>

            <ErrorMessage message={error} />
        </div>
    );
}
