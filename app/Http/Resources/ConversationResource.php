<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // جلب المشارك الآخر في المحادثة (وليس المستخدم الحالي)
        $otherParticipant = $this->participants->where('id', '!=', auth()->guard('api')->id())->first();

        return [
            'id' => $this->id,
            'conversation_with' => new UserResource($otherParticipant),
            'last_message' => new MessageResource($this->whenLoaded('messages')->first()),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
