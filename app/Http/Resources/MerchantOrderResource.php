<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantOrderResource extends JsonResource
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
            'order_id' => $this->order_id,
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'image' => $this->product->image,
                'price' => $this->product->price,
            ],
            'customer' => [
                'id' => $this->order->user->id,
                'name' => $this->order->user->name,
                'email' => $this->order->user->email,
                'phone' => $this->order->user->phone,
            ],
            'delivery_address' => [
                'id' => $this->order->userAddress->id,
                'address' => $this->order->userAddress->address,
                'city' => $this->order->userAddress->city,
                'neighborhood' => $this->order->userAddress->neighborhood,
            ],
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->quantity * $this->unit_price,
            'commission_rate' => $this->commission_rate,
            'commission_amount' => $this->commission_amount,
            'payout_amount' => $this->payout_amount,
            'order_status' => $this->order->status,
            'payment_method' => $this->order->payment_method,
            'order_date' => $this->order->created_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
