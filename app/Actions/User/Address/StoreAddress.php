<?php

namespace App\Actions\User\Address;

use App\Models\Address;
use App\Models\User;

class StoreAddress
{
    public function handle(User $user, array $address): Address
    {
        $user->clearDefaultAddress();

        return $user->addresses()->create($address);
    }
}
