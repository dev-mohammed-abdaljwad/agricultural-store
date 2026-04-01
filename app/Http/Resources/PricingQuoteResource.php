<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingQuoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'quoted_by' => $this->quoted_by,
            'delivery_fee' => (float) $this->delivery_fee,
            'total_amount' => (float) $this->total_amount,
            'notes' => $this->notes,
            'expires_at' => $this->expires_at,
            'is_expired' => $this->isExpired(),
            'status' => $this->status,
            'quoted_by_user' => UserResource::make($this->whenLoaded('quotedBy')),
            'items' => PricingQuoteItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
