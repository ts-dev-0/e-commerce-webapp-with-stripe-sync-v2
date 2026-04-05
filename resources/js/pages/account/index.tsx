import AccountCard from '@/components/account/account-card';
import AccountLayout from '@/layouts/account-layout';
import { Head } from '@inertiajs/react';
import { Lock, MapPin, ShoppingBag } from 'lucide-react';

const cards = [
    {
        title: '注文履歴',
        description: '過去の注文を確認',
        body: 'あなたの購入履歴と注文状況を確認できます。',
        href: '/account/orders',
        actionLabel: '確認する',
        icon: ShoppingBag,
    },
    {
        title: 'ログイン＆セキュリティ',
        description: 'セキュリティ設定を管理',
        body: 'パスワードやセキュリティ設定を変更できます。',
        href: '/account/settings',
        actionLabel: '設定する',
        icon: Lock,
    },
    {
        title: '住所帳',
        description: '配送先住所を管理',
        body: '配送先の住所を登録・編集できます。',
        href: '/account/addresses',
        actionLabel: '管理する',
        icon: MapPin,
    },
];

export default function Index() {
    return (
        <AccountLayout>
            <Head title="アカウント" />

            <div className="mx-auto max-w-4xl">
                <div className="grid gap-6 md:grid-cols-3">
                    {cards.map((card) => (
                        <AccountCard key={card.title} {...card} />
                    ))}
                </div>
            </div>
        </AccountLayout>
    );
}
