import { Address } from '@/types/address';

import ShippingAddressCard from './shipping-address-card';
import { RadioGroup } from '@/components/ui/radio-group';

interface Props {
    addresses: Address[];
    selectedShippingAddressId: number | null;
    setShippingAddressId: (id: number) => void;
}

export function ShippingAddressList({
    addresses,
    selectedShippingAddressId,
    setShippingAddressId,
}: Props) {
    return (
        <RadioGroup
            defaultValue={selectedShippingAddressId?.toString()}
            onValueChange={(value) => setShippingAddressId(Number(value))}
            className='flex flex-col gap-5'
        >
            {addresses.map((address) => (
                <ShippingAddressCard key={address.id} address={address} />
            ))}
        </RadioGroup>
    );
}
