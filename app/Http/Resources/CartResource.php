<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'quantity' => $this->quantity,
            'product_id' => $this->product_id,
            'product' => new ProductResource($this->product),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
