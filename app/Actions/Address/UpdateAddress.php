<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;

class UpdateAddress
{
    public function handle(User $user, Address $address, array $data): Address
    {
        if ($data['is_default'] && !$address->is_default) {
            $user->clearDefaultAddress();
        }

        $address->update($data);

        return $address;
    }
}