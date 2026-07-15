import EditPasswordForm from '@/components/edit-password-form';
import EditUserProfileForm from '@/components/edit-user-profile-form';
import AccountLayout from '@/layouts/account-layout';
import { useState } from 'react';
import { EditableCard } from './component/editable-card';
import { PasswordSummary } from './component/password-summary';
import { UserProfileSummary } from './component/user-profile-summary';

interface Props {
    name: string;
    email: string;
}

export default function Index({ name, email }: Props) {
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
