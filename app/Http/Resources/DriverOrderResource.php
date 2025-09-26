<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverOrderResource extends JsonResource
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
            'order' => [
                'id' => $this->order->id,
                'status' => $this->order->status,
                'payment_method' => $this->order->payment_method,
                'subtotal' => $this->order->subtotal,
                'shipping_total' => $this->order->shipping_total,
                'discount_total' => $this->order->discount_total,
                'grand_total' => $this->order->grand_total,
                'created_at' => $this->order->created_at,
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
                'latitude' => $this->order->userAddress->latitude,
                'longitude' => $this->order->userAddress->longitude,
            ],
            'driver' => [
                'id' => $this->driver->id,
                'name' => $this->driver->user->name,
                'phone' => $this->driver->phone_number,
                'vehicle_type' => $this->driver->vehicle_type,
                'vehicle_model' => $this->driver->vehicle_model,
                'vehicle_plate' => $this->driver->vehicle_plate_number,
            ],
            'assignment' => [
                'type' => $this->assignment_type,
                'assigned_by' => $this->assignedBy ? [
                    'id' => $this->assignedBy->id,
                    'name' => $this->assignedBy->name,
                ] : null,
                'assigned_at' => $this->assigned_at,
            ],
            'status' => $this->status,
            'timestamps' => [
                'assigned_at' => $this->assigned_at,
                'accepted_at' => $this->accepted_at,
                'picked_up_at' => $this->picked_up_at,
                'delivered_at' => $this->delivered_at,
                'cancelled_at' => $this->cancelled_at,
            ],
            'delivery_fee' => $this->delivery_fee,
            'delivery_notes' => $this->delivery_notes,
            'cancellation_reason' => $this->cancellation_reason,
            'confirmation_status' => $this->getConfirmationStatus(),
            'confirmation_data' => $this->confirmation_data,
            'duration_minutes' => $this->getDurationInMinutes(),
            'items' => $this->order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'image' => $item->product->image,
                    ],
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->quantity * $item->unit_price,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
