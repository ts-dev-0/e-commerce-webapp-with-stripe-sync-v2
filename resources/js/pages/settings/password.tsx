import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AccountLayout from '@/layouts/account-layout';
import { update } from '@/routes/user-password';

import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Password() {
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
        <AccountLayout>
            <Head title="パスワード変更" />

            <div className="mx-auto max-w-2xl">
                <div className="mb-6">
                    <h1 className="text-3xl font-bold text-slate-900">
                        パスワード変更
                    </h1>
                    <p className="mt-2 text-sm text-slate-600">
                        セキュリティのために定期的にパスワードを変更してください。
                    </p>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>新しいパスワードを設定</CardTitle>
                        <CardDescription>
                            現在のパスワードを入力し、新しいパスワードを設定してください。
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div>
                                <Label htmlFor="current_password">
                                    現在のパスワード
                                </Label>
                                <div className="relative mt-1">
                                    <Input
                                        id="current_password"
                                        type={
                                            showCurrentPassword
                                                ? 'text'
                                                : 'password'
                                        }
                                        value={data.current_password}
                                        onChange={(e) =>
                                            setData(
                                                'current_password',
                                                e.target.value,
                                            )
                                        }
                                        className="pr-10"
                                        placeholder="現在のパスワードを入力"
                                    />
                                    <button
                                        type="button"
                                        className="absolute inset-y-0 right-0 flex items-center pr-3"
                                        onClick={() =>
                                            setShowCurrentPassword(
                                                !showCurrentPassword,
                                            )
                                        }
                                    >
                                        <span className="text-sm text-slate-500">
                                            {showCurrentPassword
                                                ? '隠す'
                                                : '表示'}
                                        </span>
                                    </button>
                                </div>
                                {errors.current_password && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.current_password}
                                    </p>
                                )}
                            </div>

                            <div>
                                <Label htmlFor="password">
                                    新しいパスワード
                                </Label>
                                <div className="relative mt-1">
                                    <Input
                                        id="password"
                                        type={
                                            showNewPassword
                                                ? 'text'
                                                : 'password'
                                        }
                                        value={data.password}
                                        onChange={(e) =>
                                            setData('password', e.target.value)
                                        }
                                        className="pr-10"
                                        placeholder="新しいパスワードを入力"
                                    />
                                    <button
                                        type="button"
                                        className="absolute inset-y-0 right-0 flex items-center pr-3"
                                        onClick={() =>
                                            setShowNewPassword(!showNewPassword)
                                        }
                                    >
                                        <span className="text-sm text-slate-500">
                                            {showNewPassword ? '隠す' : '表示'}
                                        </span>
                                    </button>
                                </div>
                                {errors.password && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.password}
                                    </p>
                                )}
                            </div>

                            <div>
                                <Label htmlFor="password_confirmation">
                                    パスワード確認
                                </Label>
                                <div className="relative mt-1">
                                    <Input
                                        id="password_confirmation"
                                        type={
                                            showConfirmPassword
                                                ? 'text'
                                                : 'password'
                                        }
                                        value={data.password_confirmation}
                                        onChange={(e) =>
                                            setData(
                                                'password_confirmation',
                                                e.target.value,
                                            )
                                        }
                                        className="pr-10"
                                        placeholder="新しいパスワードを再入力"
                                    />
                                    <button
                                        type="button"
                                        className="absolute inset-y-0 right-0 flex items-center pr-3"
                                        onClick={() =>
                                            setShowConfirmPassword(
                                                !showConfirmPassword,
                                            )
                                        }
                                    >
                                        <span className="text-sm text-slate-500">
                                            {showConfirmPassword
                                                ? '隠す'
                                                : '表示'}
                                        </span>
                                    </button>
                                </div>
                                {errors.password_confirmation && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.password_confirmation}
                                    </p>
                                )}
                            </div>

                            <div className="flex justify-end">
                                <Button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-emerald-600 hover:bg-emerald-700"
                                >
                                    {processing
                                        ? '変更中...'
                                        : 'パスワードを変更'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AccountLayout>
    );
}
