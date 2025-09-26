<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'vehicle_type',
        'vehicle_model',
        'vehicle_plate_number',
        'phone_number',
        'city',
        'neighborhood',
        'latitude',
        'longitude',
        'is_available',
        'is_active',
        'is_supervisor',
        'current_orders_count',
        'rating',
        'total_deliveries',
        'working_hours',
        'service_areas',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
        'is_supervisor' => 'boolean',
        'current_orders_count' => 'integer',
        'rating' => 'decimal:2',
        'total_deliveries' => 'integer',
        'working_hours' => 'array',
        'service_areas' => 'array',
    ];

    /**
     * Get the user that owns the driver profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all orders assigned to this driver
     */
    public function driverOrders(): HasMany
    {
        return $this->hasMany(DriverOrder::class);
    }

    /**
     * Get active orders for this driver
     */
    public function activeOrders(): HasMany
    {
        return $this->hasMany(DriverOrder::class)
            ->whereIn('status', ['assigned', 'accepted', 'picked_up']);
    }

    /**
     * Get completed orders for this driver
     */
    public function completedOrders(): HasMany
    {
        return $this->hasMany(DriverOrder::class)
            ->where('status', 'delivered');
    }

    /**
     * Scope for available drivers
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where('is_active', true);
    }

    /**
     * Scope for drivers in specific city
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope for drivers in specific neighborhood
     */
    public function scopeInNeighborhood($query, $neighborhood)
    {
        return $query->where('neighborhood', $neighborhood);
    }

    /**
     * Scope for supervisor drivers
     */
    public function scopeSupervisors($query)
    {
        return $query->where('is_supervisor', true);
    }

    /**
     * Update driver's current orders count
     */
    public function updateOrdersCount()
    {
        $this->current_orders_count = $this->activeOrders()->count();
        $this->save();
    }

    /**
     * Update driver's rating
     */
    public function updateRating()
    {
        $avgRating = $this->completedOrders()
            ->whereHas('order', function($query) {
                $query->whereNotNull('driver_rating');
            })
            ->avg('order.driver_rating');

        $this->rating = $avgRating ?? 0.00;
        $this->save();
    }

    /**
     * Check if driver is working at current time
     */
    public function isWorkingNow()
    {
        $currentDay = strtolower(now()->format('l')); // monday, tuesday, etc.
        $currentTime = now()->format('H:i');

        $workingHours = $this->working_hours;

        if (!$workingHours || !isset($workingHours[$currentDay])) {
            return true; // If no working hours set, assume always working
        }

        $dayHours = $workingHours[$currentDay];

        if (!$dayHours['start'] || !$dayHours['end']) {
            return true; // If no specific hours, assume working
        }

        return $currentTime >= $dayHours['start'] && $currentTime <= $dayHours['end'];
    }

    /**
     * Check if driver serves specific area
     */
    public function servesArea($city, $neighborhood = null)
    {
        // Check if driver is in the same city
        if ($this->city !== $city) {
            return false;
        }

        // If no neighborhood specified, city match is enough
        if (!$neighborhood) {
            return true;
        }

        // Check if driver serves this neighborhood
        if ($this->neighborhood === $neighborhood) {
            return true;
        }

        // Check service areas if defined
        if ($this->service_areas && in_array($neighborhood, $this->service_areas)) {
            return true;
        }

        return false;
    }
}
