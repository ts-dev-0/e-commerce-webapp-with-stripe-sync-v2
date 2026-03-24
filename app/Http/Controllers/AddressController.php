<?php

namespace App\Http\Controllers;

use App\Actions\Address\StoreAddress;
use App\Http\Requests\StoreAddressRequest;

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
}
