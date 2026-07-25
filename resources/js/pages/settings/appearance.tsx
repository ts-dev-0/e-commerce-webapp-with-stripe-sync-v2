import AccountLayout from '@/layouts/account-layout';
import AppLayout from '@/layouts/app-layout';

export default function Appearance() {
    return (
        <AppLayout>
            <AccountLayout title="プロフィール">
                <div>Appearance Page</div>
            </AccountLayout>
        </AppLayout>
    );
}
