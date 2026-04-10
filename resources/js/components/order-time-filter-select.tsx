import { index } from '@/routes/orders';
import { router, usePage } from '@inertiajs/react';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from './ui/select';

export default function OrderTimeFilterSelect() {
    const { url } = usePage();
    const params = new URLSearchParams(url.split('?')[1]);
    const timeFilter = params.get('timeFilter') ?? 'last30';

    function handleValueChange(value: string) {
        router.get(index().url, {
            timeFilter: value,
        });
    }

    return (
        <div className="w-fit">
            <Select
                defaultValue={timeFilter}
                onValueChange={(val) => handleValueChange(val)}
            >
                <SelectTrigger>
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectItem value="last30">過去30日間</SelectItem>
                        <SelectItem value="months-3">過去3ヵ月</SelectItem>
                        <SelectItem value="year-2026">2026年</SelectItem>
                        <SelectItem value="year-2025">2025年</SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
    );
}
