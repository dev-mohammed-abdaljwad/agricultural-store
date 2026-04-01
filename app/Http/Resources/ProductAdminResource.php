<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'min_order_qty' => $this->min_order_qty,
            'is_certified' => (bool) $this->is_certified,
            'data_sheet_url' => $this->data_sheet_url,
            'usage_instructions' => $this->usage_instructions,
            'safety_instructions' => $this->safety_instructions,
            'manufacturer_info' => $this->manufacturer_info,
            'expert_tip' => $this->expert_tip,
            'expert_name' => $this->expert_name,
            'expert_title' => $this->expert_title,
            'expert_image_url' => $this->expert_image_url,
            'supplier_name' => $this->supplier_name,
            'supplier_code' => $this->supplier_code,
            'status' => $this->status,
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'primary_image' => $this->whenLoaded('primaryImage', function () {
                return [
                    'id' => $this->primaryImage?->id,
                    'url' => $this->primaryImage?->url,
                ];
            }),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'specs' => ProductSpecResource::collection($this->whenLoaded('specs')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
