<?php

namespace App\Http\Controllers;

use App\Actions\Address\DeleteAddress;
use App\Actions\Address\SetDefaultAddress;
use App\Actions\Address\StoreAddress;
use App\Actions\Address\UpdateAddress;
use App\Http\Requests\DeleteAddressRequest;
use App\Http\Requests\SetDefaultAddressRequest;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->get();

        return Inertia::render('account/addresses', [
            'data' => AddressResource::collection($addresses),
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

    public function updateDefault(SetDefaultAddressRequest $request, Address $address, SetDefaultAddress $action)
    {
        $action->handle($request->user(), $address);

        return redirect()
            ->back()
            ->with('success', 'Update default address.');
    }

    public function destroy(DeleteAddressRequest $request, DeleteAddress $action, Address $address)
    {
        $action->handle($address);

        return redirect()
            ->back()
            ->with('success', 'Deleted Address.');
    }
}
