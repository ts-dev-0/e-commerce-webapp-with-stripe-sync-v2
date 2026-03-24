<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;

class StoreAddress
{
    public function handle(User $user, array $address): Address
    {
        $hasDefault = $user->defaultAddress()->exists();

        if (!$address['is_default'] && !$hasDefault) {
            $address['is_default'] = true;
        }

        if ($address['is_default'] && $hasDefault) {
            $user->clearDefaultAddress();
        }

        return $user->addresses()->create($address);
    }
}
