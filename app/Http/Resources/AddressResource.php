<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->full_name,
            'postalCode' => $this->postal_code,
            'prefecture' => $this->prefecture,
            'city' => $this->city,
            'addressLine' => $this->address_line,
            'isDefault' => $this->is_default,
        ];
    }
}
