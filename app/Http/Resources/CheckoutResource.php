<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cartItems' => CartItemResource::collection($this->cartItems),
            'addresses' => AddressResource::collection($this->addresses),
            'defaultAddress' => AddressResource::make($this->defaultAddress),
            'anotherAddresses' => AddressResource::collection($this->anotherAddresses),
            'deliveryDate' => $this->deliveryDate,
            'shippingFee' => $this->shippingFee,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
        ];
    }
}
