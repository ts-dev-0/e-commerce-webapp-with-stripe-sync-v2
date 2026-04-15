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
            <div>
                <Label htmlFor="current_password">現在のパスワード</Label>
                <div className="relative mt-1">
                    <Input
                        id="current_password"
                        type={showCurrentPassword ? 'text' : 'password'}
                        value={data.current_password}
                        onChange={(e) =>
                            setData('current_password', e.target.value)
                        }
                        className="pr-10"
                        placeholder="現在のパスワードを入力"
                    />
                    <button
                        type="button"
                        className="absolute inset-y-0 right-0 flex items-center pr-3"
                        onClick={() =>
                            setShowCurrentPassword(!showCurrentPassword)
                        }
                    >
                        <span className="text-sm text-slate-500">
                            {showCurrentPassword ? '隠す' : '表示'}
                        </span>
                    </button>
                </div>
                <ErrorMessage
                    message={errors.current_password}
                    className="mt-2"
                />
            </div>

            <div>
                <Label htmlFor="password">新しいパスワード</Label>
                <div className="relative mt-1">
                    <Input
                        id="password"
                        type={showNewPassword ? 'text' : 'password'}
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="pr-10"
                        placeholder="新しいパスワードを入力"
                    />
                    <button
                        type="button"
                        className="absolute inset-y-0 right-0 flex items-center pr-3"
                        onClick={() => setShowNewPassword(!showNewPassword)}
                    >
                        <span className="text-sm text-slate-500">
                            {showNewPassword ? '隠す' : '表示'}
                        </span>
                    </button>
                    <ErrorMessage message={errors.password} className="mt-2" />
                </div>
            </div>

            <div>
                <Label htmlFor="password_confirmation">パスワード確認</Label>
                <div className="relative mt-1">
                    <Input
                        id="password_confirmation"
                        type={showConfirmPassword ? 'text' : 'password'}
                        value={data.password_confirmation}
                        onChange={(e) =>
                            setData('password_confirmation', e.target.value)
                        }
                        className="pr-10"
                        placeholder="新しいパスワードを再入力"
                    />
                    <button
                        type="button"
                        className="absolute inset-y-0 right-0 flex items-center pr-3"
                        onClick={() =>
                            setShowConfirmPassword(!showConfirmPassword)
                        }
                    >
                        <span className="text-sm text-slate-500">
                            {showConfirmPassword ? '隠す' : '表示'}
                        </span>
                    </button>
                </div>
            </div>

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
