<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingQuoteItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pricing_quote_id' => $this->pricing_quote_id,
            'order_item_id' => $this->order_item_id,
            'unit_price' => (float) $this->unit_price,
            'total_price' => (float) $this->total_price,
            'order_item' => OrderItemResource::make($this->whenLoaded('orderItem')),
        ];
    }
}
