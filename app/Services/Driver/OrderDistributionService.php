<?php

namespace App\Services\Driver;

use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderDistributionService
{
    /**
     * Automatically assign order to the best available driver
     */
    public function assignOrderToDriver(Order $order, $assignedBy = null)
    {
        try {
            // Get delivery address
            $deliveryAddress = $order->userAddress;
            $city = $deliveryAddress->city;
            $neighborhood = $deliveryAddress->neighborhood;

            // Find the best driver for this order
            $driver = $this->findBestDriver($city, $neighborhood);

            if (!$driver) {
                Log::warning('No available driver found for order', [
                    'order_id' => $order->id,
                    'city' => $city,
                    'neighborhood' => $neighborhood
                ]);
                return null;
            }

            // Create driver order assignment
            $driverOrder = DriverOrder::create([
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'assigned_by' => $assignedBy,
                'status' => 'assigned',
                'assignment_type' => 'auto',
                'assigned_at' => now(),
                'delivery_fee' => $this->calculateDeliveryFee($driver, $order),
            ]);

            // Update driver's orders count
            $driver->updateOrdersCount();

            Log::info('Order assigned to driver', [
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'assignment_type' => 'auto'
            ]);

            return $driverOrder;

        } catch (\Exception $e) {
            Log::error('Error assigning order to driver', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Find the best driver for the order based on location and workload
     */
    public function findBestDriver($city, $neighborhood = null)
    {
        // First priority: Drivers in the same neighborhood with lowest workload
        $neighborhoodDrivers = Driver::available()
            ->inCity($city)
            ->inNeighborhood($neighborhood)
            ->where('is_working_now', true)
            ->orderBy('current_orders_count', 'asc')
            ->orderBy('rating', 'desc')
            ->first();

        if ($neighborhoodDrivers) {
            return $neighborhoodDrivers;
        }

        // Second priority: Drivers in the same city with lowest workload
        $cityDrivers = Driver::available()
            ->inCity($city)
            ->where('is_working_now', true)
            ->orderBy('current_orders_count', 'asc')
            ->orderBy('rating', 'desc')
            ->first();

        if ($cityDrivers) {
            return $cityDrivers;
        }

        // Third priority: Any available driver with lowest workload
        $anyDriver = Driver::available()
            ->where('is_working_now', true)
            ->orderBy('current_orders_count', 'asc')
            ->orderBy('rating', 'desc')
            ->first();

        return $anyDriver;
    }

    /**
     * Manually assign order to specific driver
     */
    public function manuallyAssignOrder(Order $order, Driver $driver, $assignedBy)
    {
        try {
            // Check if driver is available
            if (!$driver->is_available || !$driver->is_active) {
                throw new \Exception('Driver is not available');
            }

            // Check if driver is working now
            if (!$driver->isWorkingNow()) {
                throw new \Exception('Driver is not working at this time');
            }

            // Create driver order assignment
            $driverOrder = DriverOrder::create([
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'assigned_by' => $assignedBy,
                'status' => 'assigned',
                'assignment_type' => 'manual',
                'assigned_at' => now(),
                'delivery_fee' => $this->calculateDeliveryFee($driver, $order),
            ]);

            // Update driver's orders count
            $driver->updateOrdersCount();

            Log::info('Order manually assigned to driver', [
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'assigned_by' => $assignedBy,
                'assignment_type' => 'manual'
            ]);

            return $driverOrder;

        } catch (\Exception $e) {
            Log::error('Error manually assigning order to driver', [
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Reassign order to different driver
     */
    public function reassignOrder(DriverOrder $driverOrder, Driver $newDriver, $assignedBy)
    {
        try {
            // Cancel current assignment
            $driverOrder->cancel('Reassigned to another driver');

            // Create new assignment
            $newDriverOrder = DriverOrder::create([
                'order_id' => $driverOrder->order_id,
                'driver_id' => $newDriver->id,
                'assigned_by' => $assignedBy,
                'status' => 'assigned',
                'assignment_type' => 'manual',
                'assigned_at' => now(),
                'delivery_fee' => $this->calculateDeliveryFee($newDriver, $driverOrder->order),
            ]);

            // Update drivers' orders count
            $driverOrder->driver->updateOrdersCount();
            $newDriver->updateOrdersCount();

            Log::info('Order reassigned to different driver', [
                'order_id' => $driverOrder->order_id,
                'old_driver_id' => $driverOrder->driver_id,
                'new_driver_id' => $newDriver->id,
                'assigned_by' => $assignedBy
            ]);

            return $newDriverOrder;

        } catch (\Exception $e) {
            Log::error('Error reassigning order', [
                'driver_order_id' => $driverOrder->id,
                'new_driver_id' => $newDriver->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Calculate delivery fee based on driver and order
     */
    private function calculateDeliveryFee(Driver $driver, Order $order)
    {
        // Get settings from database
        $baseFee = (float) \App\Models\Setting::get('delivery_base_fee', 10.00);
        $distanceFeePerKm = (float) \App\Models\Setting::get('delivery_distance_fee_per_km', 0.5);
        $minFee = (float) \App\Models\Setting::get('delivery_min_fee', 5.00);
        $maxFee = (float) \App\Models\Setting::get('delivery_max_fee', 50.00);

        // Add distance-based fee (if coordinates available)
        if ($driver->latitude && $driver->longitude && $order->userAddress && $order->userAddress->latitude && $order->userAddress->longitude) {
            $distance = $this->calculateDistance(
                $driver->latitude,
                $driver->longitude,
                $order->userAddress->latitude,
                $order->userAddress->longitude
            );

            $baseFee += $distance * $distanceFeePerKm;
        }

        // Get vehicle type multiplier from settings
        $vehicleMultiplier = match($driver->vehicle_type) {
            'car' => (float) \App\Models\Setting::get('delivery_car_multiplier', 1.0),
            'motorcycle' => (float) \App\Models\Setting::get('delivery_motorcycle_multiplier', 0.8),
            'bicycle' => (float) \App\Models\Setting::get('delivery_bicycle_multiplier', 0.6),
            default => 1.0
        };

        $finalFee = round($baseFee * $vehicleMultiplier, 2);

        // Apply min and max limits
        $finalFee = max($minFee, min($maxFee, $finalFee));

        return $finalFee;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Get available drivers for manual assignment
     */
    public function getAvailableDrivers($city = null, $neighborhood = null)
    {
        $query = Driver::available()
            ->where('is_working_now', true)
            ->with(['user', 'activeOrders']);

        if ($city) {
            $query->inCity($city);
        }

        if ($neighborhood) {
            $query->inNeighborhood($neighborhood);
        }

        return $query->orderBy('current_orders_count', 'asc')
                    ->orderBy('rating', 'desc')
                    ->get();
    }

    /**
     * Get driver statistics
     */
    public function getDriverStats(Driver $driver)
    {
        return [
            'total_orders' => $driver->driverOrders()->count(),
            'completed_orders' => $driver->completedOrders()->count(),
            'active_orders' => $driver->activeOrders()->count(),
            'cancelled_orders' => $driver->driverOrders()->cancelled()->count(),
            'average_rating' => $driver->rating,
            'total_deliveries' => $driver->total_deliveries,
            'current_workload' => $driver->current_orders_count,
            'is_available' => $driver->is_available,
            'is_working_now' => $driver->isWorkingNow(),
        ];
    }
}
