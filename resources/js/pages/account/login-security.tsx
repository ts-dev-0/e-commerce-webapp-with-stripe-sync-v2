import ErrorMessage from '@/components/error-message';
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
import { update } from '@/routes/profile';
import { Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

interface LoginSecurityProps {
    name: string;
    email: string;
}

export default function LoginSecurity({ name, email }: LoginSecurityProps) {
    const [isEditing, setIsEditing] = useState(false);
    const { data, setData, patch, processing, errors, reset } = useForm({
        name: name,
        email: email,
    });

    const handleEdit = () => {
        setIsEditing(true);
        setData({ name, email });
    };

    const handleCancel = () => {
        setIsEditing(false);
        reset();
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(update().url, {
            onSuccess: () => {
                setIsEditing(false);
                alert('アカウント情報が更新されました。');
            },
        });
    };

    return (
        <AccountLayout
            title="ログインとセキュリティ"
            description="ログイン情報とメールアドレスの管理を行います"
        >
            <div className="grid gap-6">
                <Card>
                    <CardHeader>
                        <div className="flex items-center justify-between">
                            <div>
                                <CardTitle>アカウント情報</CardTitle>
                                <CardDescription>
                                    現在のログイン名とメールアドレスです。
                                </CardDescription>
                            </div>
                            {!isEditing && (
                                <Button
                                    type="button"
                                    variant="primary"
                                    onClick={handleEdit}
                                >
                                    編集
                                </Button>
                            )}
                        </div>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        {isEditing ? (
                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div className="flex flex-col gap-2">
                                    <Label htmlFor="name">ユーザー名</Label>
                                    <Input
                                        id="name"
                                        type="text"
                                        value={data.name}
                                        onChange={(e) =>
                                            setData('name', e.target.value)
                                        }
                                        className="mt-1"
                                        placeholder="ユーザー名を入力"
                                    />
                                    <ErrorMessage message={errors.name} />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <Label htmlFor="email">
                                        メールアドレス
                                    </Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        value={data.email}
                                        onChange={(e) =>
                                            setData('email', e.target.value)
                                        }
                                        className="mt-1"
                                        placeholder="メールアドレスを入力"
                                    />
                                    <ErrorMessage message={errors.email} />
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
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                        variant="primary"
                                    >
                                        {processing ? '保存中...' : '保存'}
                                    </Button>
                                </div>
                            </form>
                        ) : (
                            <div className="flex flex-col gap-3 rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div className="flex flex-col gap-1">
                                    <p className="text-sm font-medium text-slate-700">
                                        ユーザー名
                                    </p>
                                    <p className="text-base font-semibold text-slate-900">
                                        {name}
                                    </p>
                                </div>

                                <div className="flex flex-col gap-1">
                                    <p className="text-sm font-medium text-slate-700">
                                        メールアドレス
                                    </p>
                                    <p className="text-base font-semibold text-slate-900">
                                        {email}
                                    </p>
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>パスワード</CardTitle>
                        <CardDescription>
                            セキュリティのために定期的に変更してください。
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="flex items-center justify-between gap-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <div>
                                <p className="text-sm font-medium text-slate-700">
                                    現在のパスワード
                                </p>
                                <p className="mt-1 text-base font-semibold text-slate-900">
                                    ********
                                </p>
                            </div>
                            <Button type="button" asChild variant="primary">
                                <Link href="/account/settings">変更</Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AccountLayout>
    );
}
