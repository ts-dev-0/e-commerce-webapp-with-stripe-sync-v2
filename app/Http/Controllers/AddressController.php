<?php

namespace App\Http\Controllers;

use App\Actions\Address\SetDefaultAddress;
use App\Actions\Address\StoreAddress;
use App\Http\Requests\SetDefaultAddressRequest;
use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;

class AddressController extends Controller
{
    public function store(StoreAddressRequest $request, StoreAddress $action)
    {
        $validatedData = $request->validated();

        $action->handle($request->user(), $validatedData);

        return redirect()
            ->back()
            ->with('success', 'Created Address.');
    }

    public function updateDefault(SetDefaultAddressRequest $request, Address $address, SetDefaultAddress $action)
    {
        $action->handle($request->user(), $address);

        return redirect()
            ->route('checkout.index')
            ->with('success', 'Update default address.');
    }
}
