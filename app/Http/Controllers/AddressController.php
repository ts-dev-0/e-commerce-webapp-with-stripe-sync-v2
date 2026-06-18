<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Actions\Address\SetDefaultAddress;
use App\Actions\Address\CreateAddress;
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

    public function store(StoreAddressRequest $request, CreateAddress $action)
    {
        $validatedData = $request->validated();

        $action->handle($request->user(), $validatedData);

        return back()
            ->with('success', 'Created Address.');
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $address->update($request->validated());

        return back()
            ->with('success', 'Updated Address.');
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);

        $address->query()->delete();

        return back()
            ->with('success', 'Deleted Address.');
    }

    public function updateDefault(Request $request, Address $address, SetDefaultAddress $action)
    {
        $this->authorize('setDefault', $address);

        $action->handle($request->user(), $address);

        return back()
            ->with('success', 'Update default address.');
    }
}
