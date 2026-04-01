<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'icon' => $this->icon,
            'is_active' => (bool) $this->is_active,
            'subcategories' => CategoryResource::collection($this->whenLoaded('subcategories')),
        ];
    }
}
