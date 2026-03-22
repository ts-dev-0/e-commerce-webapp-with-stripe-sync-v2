interface AddressBase {
    fullName: string;
    postalCode: string;
    prefecture: string;
    city: string;
    addressLine: string;
    phoneNumber: string;
    isDefault: boolean;
}

export type CreateAddress = AddressBase;
export interface UpdateAddress extends Partial<AddressBase> {
    id: number;
}
export interface Address extends AddressBase {
    id: number;
}
