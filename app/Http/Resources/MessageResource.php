<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_id' => $this->sender_id,
            'sender_type' => $this->sender_type,
            'body' => $this->body,
            'is_read' => (bool) $this->is_read,
            'sender' => UserResource::make($this->whenLoaded('sender')),
            'created_at' => $this->created_at,
        ];
    }
}
