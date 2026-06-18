<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\User;

class SetDefaultAddress
{
    public function handle(User $user, Address $address): void
    {
        if ($address->is_default) {
            return;
        }

        $user->clearDefaultAddress();

        $address->update(['is_default' => true]);
    }
}
