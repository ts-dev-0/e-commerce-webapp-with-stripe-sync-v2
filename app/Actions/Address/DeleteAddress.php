<?php

namespace App\Actions\Address;

use App\Models\Address;

class DeleteAddress
{
    public function handle(Address $address): void
    {
        $address->delete();
    }
}
