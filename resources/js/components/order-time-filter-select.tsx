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

interface Years {
    label: string;
    value: string;
}
interface Props {
    years: Years[];
}

export default function OrderTimeFilterSelect({ years }: Props) {
    const { url } = usePage();
    const params = new URLSearchParams(url.split('?')[1]);
    const timeFilter = params.get('timeFilter') ?? 'last30';

    function handleValueChange(value: string) {
        router.get(index().url, {
            timeFilter: value,
        });
    }

    return (
        <div className="mb-6 w-fit">
            <Select
                defaultValue={timeFilter}
                onValueChange={(val) => handleValueChange(val)}
            >
                <SelectTrigger className="bg-white">
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectItem value="last30">過去30日間</SelectItem>
                        <SelectItem value="months-3">過去3ヵ月</SelectItem>
                        {years.map(({ label, value }) => (
                            <SelectItem key={label} value={value}>
                                {label}年
                            </SelectItem>
                        ))}
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
    );
}
