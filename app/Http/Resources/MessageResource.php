<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'body' => $this->body,
            'type' => $this->type,
            'attachment_url' => $this->attachment_url ? asset('storage/' . $this->attachment_url) : null,
            'sent_by' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at->diffForHumans(), // '5 minutes ago'
            'timestamp' => $this->created_at,
        ];
    }
}
