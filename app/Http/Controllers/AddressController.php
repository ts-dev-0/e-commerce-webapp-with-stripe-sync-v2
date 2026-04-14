<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Actions\Address\DeleteAddress;
use App\Actions\Address\SetDefaultAddress;
use App\Actions\Address\StoreAddress;
use App\Actions\Address\UpdateAddress;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->get();

        return Inertia::render('account/addresses', [
            'addresses' => AddressResource::collection($addresses),
        ]);
    }

    public function store(StoreAddressRequest $request, StoreAddress $action)
    {
        $validatedData = $request->validated();

        $action->handle($request->user(), $validatedData);

        return redirect()
            ->back()
            ->with('success', 'Created Address.');
    }

    public function update(UpdateAddressRequest $request, Address $address, UpdateAddress $action)
    {
        $validatedData = $request->validated();

        $action->handle($request->user(), $address, $validatedData);

        return redirect()
            ->back()
            ->with('success', 'Updated Address.');
    }

    public function updateDefault(Request $request, Address $address, SetDefaultAddress $action)
    {
        $this->authorize('setDefault', $address);

        $action->handle($request->user(), $address);

        return redirect()
            ->back()
            ->with('success', 'Update default address.');
    }

    public function destroy(DeleteAddress $action, Address $address)
    {
        $this->authorize('delete', $address);

        $action->handle($address);

        return redirect()
            ->back()
            ->with('success', 'Deleted Address.');
    }
}
