import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AccountLayout from '@/layouts/account-layout';
import { Link } from '@inertiajs/react';

interface LoginSecurityProps {
    name: string;
    email: string;
}

export default function LoginSecurity({ name, email }: LoginSecurityProps) {
    return (
        <AccountLayout>
            <div className="mx-auto max-w-4xl space-y-6">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">
                        ログインとセキュリティ
                    </h1>
                    <p className="mt-2 text-sm text-slate-600">
                        ログイン情報とメールアドレスの管理を行います。
                    </p>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>アカウント情報</CardTitle>
                        <CardDescription>
                            現在のログイン名とメールアドレスです。
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <div className="flex flex-col gap-3 rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <div className="flex flex-col gap-1">
                                <div className="flex items-center justify-between gap-4">
                                    <p className="text-sm font-medium text-slate-700">
                                        ユーザー名
                                    </p>
                                    <Button
                                        asChild
                                        variant="outline"
                                        className="text-slate-900"
                                    >
                                        <Link href="/account/profile">
                                            編集
                                        </Link>
                                    </Button>
                                </div>
                                <p className="text-base font-semibold text-slate-900">
                                    {name}
                                </p>
                            </div>

                            <div className="flex flex-col gap-1">
                                <div className="flex items-center justify-between gap-4">
                                    <p className="text-sm font-medium text-slate-700">
                                        メールアドレス
                                    </p>
                                    <Button
                                        asChild
                                        variant="outline"
                                        className="text-slate-900"
                                    >
                                        <Link href="/account/profile">
                                            編集
                                        </Link>
                                    </Button>
                                </div>
                                <p className="text-base font-semibold text-slate-900">
                                    {email}
                                </p>
                            </div>
                        </div>
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
                            <Button
                                asChild
                                variant="outline"
                                className="text-slate-900"
                            >
                                <Link href="/account/settings">変更</Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AccountLayout>
    );
}
