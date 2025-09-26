<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverOrderResource;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use App\Services\Driver\OrderDistributionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriverSupervisorController extends Controller
{
    protected $orderDistributionService;

    public function __construct(OrderDistributionService $orderDistributionService)
    {
        $this->orderDistributionService = $orderDistributionService;
    }

    /**
     * Get supervisor dashboard
     */
    public function dashboard()
    {
        $supervisor = Auth::guard('api')->user();

        // Check if user is a driver supervisor
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $stats = [
            'total_drivers' => Driver::count(),
            'active_drivers' => Driver::where('is_active', true)->count(),
            'available_drivers' => Driver::available()->count(),
            'total_orders' => DriverOrder::count(),
            'pending_orders' => DriverOrder::where('status', 'assigned')->count(),
            'in_progress_orders' => DriverOrder::whereIn('status', ['accepted', 'picked_up'])->count(),
            'completed_orders' => DriverOrder::where('status', 'delivered')->count(),
            'cancelled_orders' => DriverOrder::where('status', 'cancelled')->count(),
        ];

        $recentOrders = DriverOrder::with(['order.user', 'driver.user', 'order.userAddress'])
            ->latest()
            ->limit(10)
            ->get();

        $driversNeedingAttention = Driver::where('is_active', true)
            ->where('current_orders_count', '>', 5) // Drivers with high workload
            ->orWhere('rating', '<', 3.0) // Drivers with low rating
            ->with('user')
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'statistics' => $stats,
                'recent_orders' => DriverOrderResource::collection($recentOrders),
                'drivers_needing_attention' => DriverResource::collection($driversNeedingAttention),
            ]
        ]);
    }

    /**
     * Get all drivers
     */
    public function getDrivers(Request $request)
    {
        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $query = Driver::with(['user', 'activeOrders']);

        // Apply filters
        if ($request->has('city')) {
            $query->inCity($request->city);
        }

        if ($request->has('neighborhood')) {
            $query->inNeighborhood($request->neighborhood);
        }

        if ($request->has('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        $drivers = $query->orderBy('current_orders_count', 'asc')
            ->orderBy('rating', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => DriverResource::collection($drivers)
        ]);
    }

    /**
     * Get driver details
     */
    public function getDriver($driverId)
    {
        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $targetDriver = Driver::with(['user', 'driverOrders.order.user'])
            ->find($driverId);

        if (!$targetDriver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found'
            ], 404);
        }

        $stats = $this->orderDistributionService->getDriverStats($targetDriver);

        return response()->json([
            'status' => true,
            'data' => [
                'driver' => new DriverResource($targetDriver),
                'statistics' => $stats,
            ]
        ]);
    }

    /**
     * Get all orders
     */
    public function getOrders(Request $request)
    {
        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $query = DriverOrder::with(['order.user', 'driver.user', 'order.userAddress', 'order.items.product']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->has('assignment_type')) {
            $query->where('assignment_type', $request->assignment_type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('assigned_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => DriverOrderResource::collection($orders)
        ]);
    }

    /**
     * Manually assign order to driver
     */
    public function assignOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $order = Order::find($request->order_id);
        $targetDriver = Driver::find($request->driver_id);

        // Check if order is already assigned
        $existingAssignment = DriverOrder::where('order_id', $order->id)
            ->whereIn('status', ['assigned', 'accepted', 'picked_up'])
            ->first();

        if ($existingAssignment) {
            return response()->json([
                'status' => false,
                'message' => 'Order is already assigned to another driver'
            ], 400);
        }

        try {
            $driverOrder = $this->orderDistributionService->manuallyAssignOrder(
                $order,
                $targetDriver,
                $supervisor->id
            );

            return response()->json([
                'status' => true,
                'message' => 'Order assigned successfully',
                'data' => new DriverOrderResource($driverOrder)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reassign order to different driver
     */
    public function reassignOrder(Request $request, $driverOrderId)
    {
        $validator = Validator::make($request->all(), [
            'new_driver_id' => 'required|exists:drivers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $driverOrder = DriverOrder::find($driverOrderId);
        $newDriver = Driver::find($request->new_driver_id);

        if (!$driverOrder) {
            return response()->json([
                'status' => false,
                'message' => 'Driver order not found'
            ], 404);
        }

        try {
            $newDriverOrder = $this->orderDistributionService->reassignOrder(
                $driverOrder,
                $newDriver,
                $supervisor->id
            );

            return response()->json([
                'status' => true,
                'message' => 'Order reassigned successfully',
                'data' => new DriverOrderResource($newDriverOrder)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Confirm order delivery (admin/supervisor confirmation)
     */
    public function confirmDelivery(Request $request, $driverOrderId)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $driverOrder = DriverOrder::find($driverOrderId);

        if (!$driverOrder) {
            return response()->json([
                'status' => false,
                'message' => 'Driver order not found'
            ], 404);
        }

        if ($driverOrder->status !== 'delivered') {
            return response()->json([
                'status' => false,
                'message' => 'Order is not marked as delivered by driver'
            ], 400);
        }

        // Update confirmation data
        $confirmationData = $driverOrder->confirmation_data ?? [];
        $confirmationData['admin_confirmed'] = true;
        $confirmationData['admin_confirmed_by'] = $supervisor->id;
        $confirmationData['admin_confirmed_at'] = now();
        $confirmationData['admin_notes'] = $request->notes;

        $driverOrder->update([
            'confirmation_data' => $confirmationData
        ]);

        // Update order status to completed if fully confirmed
        $confirmationStatus = $driverOrder->getConfirmationStatus();
        if ($confirmationStatus['is_fully_confirmed']) {
            $driverOrder->order->update(['status' => 'completed']);
        }

        return response()->json([
            'status' => true,
            'message' => 'Delivery confirmed successfully',
            'data' => new DriverOrderResource($driverOrder)
        ]);
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Request $request, $driverOrderId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $driverOrder = DriverOrder::find($driverOrderId);

        if (!$driverOrder) {
            return response()->json([
                'status' => false,
                'message' => 'Driver order not found'
            ], 404);
        }

        if (!$driverOrder->canBeCancelled()) {
            return response()->json([
                'status' => false,
                'message' => 'Order cannot be cancelled at this stage'
            ], 400);
        }

        $driverOrder->cancel($request->reason);

        return response()->json([
            'status' => true,
            'message' => 'Order cancelled successfully',
            'data' => new DriverOrderResource($driverOrder)
        ]);
    }

    /**
     * Update driver status
     */
    public function updateDriverStatus(Request $request, $driverId)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
            'is_supervisor' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $targetDriver = Driver::find($driverId);

        if (!$targetDriver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found'
            ], 404);
        }

        $targetDriver->update($request->only(['is_active', 'is_available', 'is_supervisor']));

        return response()->json([
            'status' => true,
            'message' => 'Driver status updated successfully',
            'data' => new DriverResource($targetDriver)
        ]);
    }

    /**
     * Get available drivers for assignment
     */
    public function getAvailableDrivers(Request $request)
    {
        $supervisor = Auth::guard('api')->user();

        // Check supervisor privileges
        $driver = $supervisor->driver;
        if (!$driver || !$driver->is_supervisor) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Supervisor privileges required.'
            ], 403);
        }

        $drivers = $this->orderDistributionService->getAvailableDrivers(
            $request->city,
            $request->neighborhood
        );

        return response()->json([
            'status' => true,
            'data' => DriverResource::collection($drivers)
        ]);
    }
}
