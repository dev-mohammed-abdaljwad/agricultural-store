<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'delivery_fee' => (float) $this->delivery_fee,
            'total_amount' => (float) $this->total_amount,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'supplier_ref' => $this->supplier_ref,
            'admin_notes' => $this->admin_notes,
            'delivery_address' => $this->delivery_address,
            'delivery_governorate' => $this->delivery_governorate,
            'customer' => UserResource::make($this->whenLoaded('customer')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'active_quote' => PricingQuoteResource::make($this->whenLoaded('activeQuote')),
            'quotes' => PricingQuoteResource::collection($this->whenLoaded('quotes')),
            'tracking' => OrderTrackingResource::collection($this->whenLoaded('tracking')),
            'conversation' => ConversationResource::make($this->whenLoaded('conversation')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
