<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'orderId' => $this->id,
            'status' => $this->status->name,
            'totalAmount' => $this->total_amount,
            'orderedAt' => $this->ordered_at,
            'fullName' => $this->full_name,
            'postalCode' => $this->postal_code,
            'prefecture' => $this->prefecture,
            'city' => $this->city,
            'addressLine' => $this->address_line,
            'phoneNumber' => $this->phone_number,
            'items' => OrderItemResource::collection($this->items),
        ];
    }
}
