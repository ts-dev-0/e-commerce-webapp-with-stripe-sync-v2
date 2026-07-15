import AccountLayout from '@/layouts/account-layout';
import OrderTimeFilterSelect from '@/pages/account/order/component/order-time-filter-select';

import { EmptyState } from '@/components/empty-state';
import { Order } from '@/types/order';
import { Head } from '@inertiajs/react';
import { OrderCard } from './component/order-card';

interface Years {
    label: string;
    value: string;
}

interface Props {
    orders: Order[];
    years: Years[];
}

export default function Index({ orders, years }: Props) {
    return (
        <AccountLayout title="注文履歴" description="過去の注文一覧です">
            <Head title="注文履歴" />

            <OrderTimeFilterSelect years={years} />
            {orders.length === 0 ? (
                <EmptyState title="注文履歴がありません" />
            ) : (
                <div className="flex flex-col gap-4">
                    {orders.map((order) => (
                        <OrderCard key={order.orderId} order={order} />
                    ))}
                </div>
            )}
        </AccountLayout>
    );
}
