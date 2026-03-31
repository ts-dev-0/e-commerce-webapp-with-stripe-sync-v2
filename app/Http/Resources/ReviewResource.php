<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'userId' => $this->user_id,
            'productId' => $this->product_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'createdAt' => $this->created_at?->diffForHumans(),
            'updatedAt' => $this->updated_at?->diffForHumans(),
            'isEdited' => $this->updated_at?->ne($this->created_at),
        ];
    }
}
