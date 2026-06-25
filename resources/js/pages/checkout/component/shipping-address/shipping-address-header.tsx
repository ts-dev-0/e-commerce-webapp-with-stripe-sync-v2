import { Button } from '@/components/ui/button';

interface Props {
    onClick: () => void;
}

export function ShippingAddressHeader({ onClick }: Props) {
    return (
        <div className="flex items-center justify-between">
            <h2 className="text-lg font-semibold text-slate-800">
                登録済み配送先
            </h2>

            <Button variant="link" onClick={onClick}>
                新しい配送先を登録する
            </Button>
        </div>
    );
}
