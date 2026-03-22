import { store } from '@/routes/addresses';
import { CreateAddress } from '@/types/address';
import { router } from '@inertiajs/react';
import { useState } from 'react';

export function useAddress() {
    const initialAddress = {
        fullName: '',
        postalCode: '',
        prefecture: '東京都',
        city: '',
        addressLine: '',
        phoneNumber: '',
        isDefault: false,
    };

    const [address, setAddress] = useState<CreateAddress>({
        ...initialAddress,
    });

    const handleCreateAddress = () => {
        router.post(
            store().url,
            {
                full_name: address.fullName,
                postal_code: address['postalCode'],
                prefecture: address['prefecture'],
                city: address['city'],
                address_line: address['addressLine'],
                phone_number: address['phoneNumber'],
                is_default: address['isDefault'],
            },
            {
                preserveScroll: true,
                preserveState: false,
                onSuccess: (page) => {
                    console.log('Communication successful:', page);
                    setAddress({ ...initialAddress });
                },
                onError: (errors) => {
                    console.error('Back-end error:', errors);
                },
            },
        );
    };

    const handleFullName = (fullName: string) => {
        setAddress((prev) => ({
            ...prev,
            fullName,
        }));
    };

    const handlePostalCode = (postalCode: string) => {
        setAddress((prev) => ({
            ...prev,
            postalCode,
        }));
    };

    const handlePrefecture = (prefecture: string) => {
        setAddress((prev) => ({
            ...prev,
            prefecture,
        }));
    };

    const handleCity = (city: string) => {
        setAddress((prev) => ({
            ...prev,
            city,
        }));
    };

    const handleAddressLine = (addressLine: string) => {
        setAddress((prev) => ({
            ...prev,
            addressLine,
        }));
    };

    const handlePhoneNumber = (phoneNumber: string) => {
        setAddress((prev) => ({
            ...prev,
            phoneNumber,
        }));
    };

    const handleIsDefault = (isDefault: boolean) => {
        setAddress((prev) => ({
            ...prev,
            isDefault,
        }));
    };

    return {
        address,
        handleCreateAddress,
        handleFullName,
        handlePostalCode,
        handlePrefecture,
        handleCity,
        handleAddressLine,
        handlePhoneNumber,
        handleIsDefault,
    };
}
