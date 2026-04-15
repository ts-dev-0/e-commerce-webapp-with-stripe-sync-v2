import EditPasswordForm from '@/components/edit-password-form';
import EditUserProfileForm from '@/components/edit-user-profile-form';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AccountLayout from '@/layouts/account-layout';
import { useState } from 'react';

interface LoginSecurityProps {
    name: string;
    email: string;
}

export default function LoginSecurity({ name, email }: LoginSecurityProps) {
    const [isUserProfileEditing, setIsUserProfileEditing] = useState(false);
    const [isPasswordEditing, setIsPasswordEditing] = useState(false);

    return (
        <AccountLayout
            title="ログインとセキュリティ"
            description="ログイン情報とメールアドレスの管理を行います"
        >
            <div className="grid gap-6">
                <EditableCard
                    title="アカウント情報"
                    description="現在のログイン名とメールアドレスです。"
                    isEditing={isUserProfileEditing}
                    onEdit={() => setIsUserProfileEditing(true)}
                    edit={
                        <EditUserProfileForm
                            name={name}
                            email={email}
                            handleCancel={() => setIsUserProfileEditing(false)}
                        />
                    }
                    view={<UserProfileSummary name={name} email={email} />}
                />

                <EditableCard
                    title="パスワード"
                    description="セキュリティのために定期的に変更してください。"
                    isEditing={isPasswordEditing}
                    onEdit={() => setIsPasswordEditing(true)}
                    edit={
                        <EditPasswordForm
                            handleCancel={() => setIsPasswordEditing(false)}
                        />
                    }
                    view={<PasswordSummary />}
                />
            </div>
        </AccountLayout>
    );
}

type EditableCardProps = {
    title: string;
    description: string;
    isEditing: boolean;
    onEdit: () => void;
    view: React.ReactNode;
    edit: React.ReactNode;
    editLabel?: string;
};

function EditableCard({
    title,
    description,
    isEditing,
    onEdit,
    view,
    edit,
    editLabel = '編集',
}: EditableCardProps) {
    return (
        <Card>
            <CardHeader>
                <div className="flex items-center justify-between">
                    <div>
                        <CardTitle>{title}</CardTitle>
                        <CardDescription>{description}</CardDescription>
                    </div>

                    {!isEditing && (
                        <Button
                            type="button"
                            variant="primary"
                            onClick={onEdit}
                        >
                            {editLabel}
                        </Button>
                    )}
                </div>
            </CardHeader>

            <CardContent>{isEditing ? edit : view}</CardContent>
        </Card>
    );
}

type UserProfileSummaryProps = {
    name: string;
    email: string;
};

export function UserProfileSummary({ name, email }: UserProfileSummaryProps) {
    return (
        <div className="flex flex-col gap-3 rounded-lg border border-slate-200 bg-slate-50 p-4">
            <div className="flex flex-col gap-1">
                <p className="text-sm font-medium text-slate-700">ユーザー名</p>

                <p className="text-base font-semibold text-slate-900">{name}</p>
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
    );
}

export function PasswordSummary() {
    return (
        <div className="flex items-center justify-between gap-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
            <div className="flex flex-col gap-1">
                <p className="text-sm font-medium text-slate-700">
                    現在のパスワード
                </p>

                <p className="text-base font-semibold tracking-widest text-slate-900">
                    ********
                </p>
            </div>
        </div>
    );
}
