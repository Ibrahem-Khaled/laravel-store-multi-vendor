<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ],
            'license_number' => $this->license_number,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_model' => $this->vehicle_model,
            'vehicle_plate_number' => $this->vehicle_plate_number,
            'phone_number' => $this->phone_number,
            'city' => $this->city,
            'neighborhood' => $this->neighborhood,
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'status' => [
                'is_available' => $this->is_available,
                'is_active' => $this->is_active,
                'is_supervisor' => $this->is_supervisor,
                'is_working_now' => $this->isWorkingNow(),
            ],
            'statistics' => [
                'current_orders_count' => $this->current_orders_count,
                'rating' => $this->rating,
                'total_deliveries' => $this->total_deliveries,
            ],
            'working_hours' => $this->working_hours,
            'service_areas' => $this->service_areas,
            'active_orders_count' => $this->activeOrders->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
