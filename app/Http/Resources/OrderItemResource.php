<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price ? (float) $this->unit_price : null,
            'total_price' => $this->total_price ? (float) $this->total_price : null,
            'product' => ProductResource::make($this->whenLoaded('product')),
            'created_at' => $this->created_at,
        ];
    }
}
