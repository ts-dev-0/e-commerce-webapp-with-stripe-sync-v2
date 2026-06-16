<?php

namespace App\Actions\Address;

use App\Models\User;

class CreateAddress
{    
    public function handle(User $user, array $addressData): void
    {
        $hasDefault = $user->defaultAddress()->exists();

        if (! $hasDefault) {
            $addressData['is_default'] = true;
        }

        if ($hasDefault && $addressData['is_default']) {
            $user->clearDefaultAddress();
        }


        $user->addresses()->create($addressData);
    }
}
