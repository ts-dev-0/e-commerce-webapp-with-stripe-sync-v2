import { Label } from '@/components/ui/label';
import { RadioGroupItem } from '@/components/ui/radio-group';
import { Address } from '@/types/address';

interface AddressCardProps {
    address: Address;
}

export default function ShippingAddressCard({ address }: AddressCardProps) {
    const addressId = address.id.toString();

    return (
        <div className="flex items-center gap-3">
            <RadioGroupItem id={addressId} value={addressId} />
            <Label htmlFor={addressId} className="flex flex-col gap-2">
                <p className="font-bold">{address.fullName}</p>
                <p>
                    {address.postalCode}, {address.prefecture}, {address.city}{' '}
                    {address.addressLine}
                </p>
                {address.phoneNumber && <p>電話番号: {address.phoneNumber}</p>}
            </Label>
        </div>
    );
}
